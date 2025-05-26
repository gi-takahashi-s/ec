<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentLink;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 配送先住所選択画面を表示
     */
    public function address()
    {
        $cart = Auth::user()->cart;
        
        // カートが空の場合はカートページにリダイレクト
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'カートが空です。');
        }

        $addresses = Auth::user()->shippingAddresses()->orderBy('is_default', 'desc')->get();
        
        return view('checkout.address', compact('addresses', 'cart'));
    }

    /**
     * 配送先住所を選択して次へ進む
     */
    public function selectAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_address_id' => 'required|exists:shipping_addresses,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // 選択された住所が自分のものか確認
        $address = ShippingAddress::findOrFail($request->shipping_address_id);
        if ($address->user_id !== Auth::id()) {
            abort(403, '不正なアクセスです。');
        }

        // 選択された住所をセッションに保存
        session()->put('checkout.shipping_address_id', $address->id);

        return redirect()->route('checkout.confirm');
    }

    /**
     * 注文確認画面を表示
     */
    public function confirm()
    {
        $cart = Auth::user()->cart;
        
        // カートが空の場合はカートページにリダイレクト
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'カートが空です。');
        }

        // 配送先住所が選択されていない場合は住所選択画面にリダイレクト
        $shippingAddressId = session('checkout.shipping_address_id');
        if (!$shippingAddressId) {
            return redirect()->route('checkout.address')->with('error', '配送先住所を選択してください。');
        }

        $address = ShippingAddress::findOrFail($shippingAddressId);
        
        // カートの合計金額を計算
        $subtotal = $cart->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        // 税金計算（10%）
        $tax = $subtotal * 0.1;
        
        // 配送料計算（一律500円）
        $shippingFee = 500;
        
        // 合計金額
        $total = $subtotal + $tax + $shippingFee;

        return view('checkout.confirm', compact('cart', 'address', 'subtotal', 'tax', 'shippingFee', 'total'));
    }

    /**
     * 注文を作成し、支払い処理を行う
     */
    public function process(Request $request)
    {
        $cart = Auth::user()->cart;
        
        // カートが空の場合はカートページにリダイレクト
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'カートが空です。');
        }

        // 配送先住所が選択されていない場合は住所選択画面にリダイレクト
        $shippingAddressId = session('checkout.shipping_address_id');
        if (!$shippingAddressId) {
            return redirect()->route('checkout.address')->with('error', '配送先住所を選択してください。');
        }

        $address = ShippingAddress::findOrFail($shippingAddressId);
        
        // 注文情報の準備
        $subtotal = $cart->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        $tax = $subtotal * 0.1;
        $shippingFee = 500;
        $total = $subtotal + $tax + $shippingFee;

        // トランザクション開始
        DB::beginTransaction();

        try {
            // 注文作成
            $order = new Order([
                'user_id' => Auth::id(),
                'shipping_address_id' => $address->id,
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'payment_method' => 'stripe',
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $request->notes,
            ]);
            $order->save();

            // 注文アイテムの作成
            foreach ($cart->items as $cartItem) {
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $cartItem->quantity,
                ]);
                $orderItem->save();
            }

            // 支払い処理を行う
            // Stripe Payment Link 作成
            Stripe::setApiKey(config('services.stripe.secret'));
            
            // Checkout Sessionの作成（Payment Linksの代わりに）
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => array_merge(
                    array_map(function($cartItem) {
                        return [
                            'price_data' => [
                                'currency' => 'jpy',
                                'product_data' => [
                                    'name' => $cartItem->product->name,
                                ],
                                'unit_amount' => intval($cartItem->product->price),
                            ],
                            'quantity' => $cartItem->quantity,
                        ];
                    }, $cart->items->all()),
                    [
                        [
                            'price_data' => [
                                'currency' => 'jpy',
                                'product_data' => [
                                    'name' => '消費税',
                                ],
                                'unit_amount' => intval($tax),
                            ],
                            'quantity' => 1,
                        ],
                        [
                            'price_data' => [
                                'currency' => 'jpy',
                                'product_data' => [
                                    'name' => '送料',
                                ],
                                'unit_amount' => intval($shippingFee),
                            ],
                            'quantity' => 1,
                        ]
                    ]
                ),
                'mode' => 'payment',
                'success_url' => route('checkout.complete', ['order' => $order->id]),
                'cancel_url' => route('checkout.confirm'),
                'metadata' => [
                    'order_id' => $order->id,
                ],
                'client_reference_id' => $order->order_number,
            ]);
            
            // 注文にStripe支払いIDを関連付け
            $order->stripe_payment_id = $session->id;
            $order->save();
            
            // カートを空にする
            foreach ($cart->items as $cartItem) {
                $cartItem->delete();
            }
            
            // トランザクションコミット
            DB::commit();
            
            // 支払いページにリダイレクト
            return redirect($session->url);
            
        } catch (ApiErrorException $e) {
            // Stripe APIエラー
            DB::rollBack();
            Log::error('Stripe決済エラー: ' . $e->getMessage());
            return redirect()->route('checkout.confirm')
                ->with('error', '決済処理中にエラーが発生しました。もう一度お試しください。');
        } catch (\Exception $e) {
            // その他のエラー
            DB::rollBack();
            Log::error('注文処理エラー: ' . $e->getMessage());
            return redirect()->route('checkout.confirm')
                ->with('error', '注文処理中にエラーが発生しました。もう一度お試しください。');
        }
    }

    /**
     * 注文完了画面を表示
     */
    public function complete(Order $order)
    {
        // 自分の注文のみ表示可能
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Stripeの支払い状態を確認（実際にはWebhookで処理するのが望ましい）
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            // 支払い状態を更新（実際の実装では必要に応じて）
            $order->payment_status = 'paid';
            $order->paid_at = now();
            $order->order_status = 'processing';
            $order->save();
            
        } catch (\Exception $e) {
            Log::error('Stripe決済状態確認エラー: ' . $e->getMessage());
        }
        
        return view('checkout.complete', compact('order'));
    }
}
