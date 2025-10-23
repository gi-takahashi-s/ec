<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use App\Models\BankTransfer;
use App\Models\PaymentMethod;
use App\Notifications\OrderReceivedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentLink;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Carbon\Carbon;

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

        return redirect()->route('checkout.shipping');
    }

    /**
     * 配送業者・配送時間選択画面を表示
     */
    public function shipping()
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

        // 有効な配送業者を取得
        $shippingCompanies = \App\Models\ShippingCompany::active()->ordered()->get();

        return view('checkout.shipping', compact('cart', 'shippingCompanies'));
    }

    /**
     * 配送業者・配送時間を選択して次へ進む
     */
    public function selectShipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_company_id' => 'required|exists:shipping_companies,id',
            'delivery_time' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // 選択された配送業者が有効か確認
        $shippingCompany = \App\Models\ShippingCompany::active()->findOrFail($request->shipping_company_id);

        // 配送情報をセッションに保存
        session()->put('checkout.shipping_company_id', $shippingCompany->id);
        session()->put('checkout.delivery_time', $request->delivery_time);

        return redirect()->route('checkout.payment-method');
    }

    /**
     * 支払い方法選択画面を表示
     */
    public function paymentMethod()
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

        // 配送業者が選択されていない場合は配送選択画面にリダイレクト
        $shippingCompanyId = session('checkout.shipping_company_id');
        if (!$shippingCompanyId) {
            return redirect()->route('checkout.shipping')->with('error', '配送業者を選択してください。');
        }

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

        // 有効な決済方法を取得
        $paymentMethods = \App\Models\PaymentMethod::getEnabled();

        return view('checkout.payment-method', compact('subtotal', 'tax', 'shippingFee', 'total', 'paymentMethods'));
    }

    /**
     * 支払い方法を選択して次へ進む
     */
    public function storePaymentMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:stripe,bank_transfer,cash_on_delivery',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // 支払い方法をセッションに保存
        session()->put('checkout.payment_method', $request->payment_method);

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

        // 支払い方法が選択されていない場合は支払い方法選択画面にリダイレクト
        $paymentMethod = session('checkout.payment_method');
        if (!$paymentMethod) {
            return redirect()->route('checkout.payment-method')->with('error', '支払い方法を選択してください。');
        }

        $address = ShippingAddress::findOrFail($shippingAddressId);
        
        // 商品データと画像をEagerロード
        $cart->load(['items.product.mainImage']);
        
        // カートの合計金額を計算
        $subtotal = $cart->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        // 税金計算（10%）
        $tax = $subtotal * 0.1;
        
        // 配送料計算（一律500円）
        $shippingFee = 500;
        
        // 代引手数料計算
        $codFee = 0;
        if ($paymentMethod === 'cash_on_delivery') {
            $paymentMethodModel = \App\Models\PaymentMethod::getByKey('cash_on_delivery');
            if ($paymentMethodModel) {
                $codFee = $paymentMethodModel->calculateCodFee($subtotal);
            }
        }
        
        // 合計金額
        $total = $subtotal + $tax + $shippingFee + $codFee;

        // 決済方法の詳細情報を取得
        $paymentMethodDetails = null;
        if ($paymentMethod === 'bank_transfer') {
            $paymentMethodDetails = \App\Models\PaymentMethod::getByKey('bank_transfer');
        } elseif ($paymentMethod === 'cash_on_delivery') {
            $paymentMethodDetails = \App\Models\PaymentMethod::getByKey('cash_on_delivery');
        }

        // 配送業者が選択されていない場合は配送選択画面にリダイレクト
        $shippingCompanyId = session('checkout.shipping_company_id');
        if (!$shippingCompanyId) {
            return redirect()->route('checkout.shipping')->with('error', '配送業者を選択してください。');
        }

        $shippingCompany = \App\Models\ShippingCompany::findOrFail($shippingCompanyId);
        $deliveryTime = session('checkout.delivery_time');

        return view('checkout.confirm', compact('cart', 'address', 'shippingCompany', 'deliveryTime', 'paymentMethod', 'paymentMethodDetails', 'subtotal', 'tax', 'shippingFee', 'codFee', 'total'));
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

        // 支払い方法が選択されていない場合は支払い方法選択画面にリダイレクト
        $paymentMethod = session('checkout.payment_method');
        if (!$paymentMethod) {
            return redirect()->route('checkout.payment-method')->with('error', '支払い方法を選択してください。');
        }

        $address = ShippingAddress::findOrFail($shippingAddressId);
        
        // 商品データと画像をEagerロード
        $cart->load(['items.product.mainImage']);
        
        // 注文情報の準備
        $subtotal = $cart->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        $tax = $subtotal * 0.1;
        $shippingFee = 500;
        
        // 代引手数料計算
        $codFee = 0;
        if ($paymentMethod === 'cash_on_delivery') {
            $paymentMethodModel = \App\Models\PaymentMethod::getByKey('cash_on_delivery');
            if ($paymentMethodModel) {
                $codFee = $paymentMethodModel->calculateCodFee($subtotal);
            }
        }
        
        $total = $subtotal + $tax + $shippingFee + $codFee;

        // トランザクション開始
        DB::beginTransaction();

        try {
            // 配送情報を取得
            $shippingCompanyId = session('checkout.shipping_company_id');
            $deliveryTime = session('checkout.delivery_time');

            // 注文作成
            $order = new Order([
                'user_id' => Auth::id(),
                'shipping_address_id' => $address->id,
                'shipping_company_id' => $shippingCompanyId,
                'selected_delivery_time' => $deliveryTime,
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $request->notes,
            ]);

            // 銀行振込の場合は振込期限を設定
            if ($paymentMethod === Order::PAYMENT_METHOD_BANK_TRANSFER) {
                $order->transfer_deadline = Carbon::now()->addDays(7);
            }

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

            // 支払い方法に応じた処理
            if ($paymentMethod === Order::PAYMENT_METHOD_STRIPE) {
                // Stripe決済処理
                $this->processStripePayment($order, $cart, $subtotal, $tax, $shippingFee);
            } elseif ($paymentMethod === Order::PAYMENT_METHOD_BANK_TRANSFER) {
                // 銀行振込情報の作成
                $this->createBankTransfer($order, $total);
            }
            
            // カートを空にする
            foreach ($cart->items as $cartItem) {
                $cartItem->delete();
            }
            
            // セッションをクリア
            session()->forget([
                'checkout.shipping_address_id', 
                'checkout.shipping_company_id', 
                'checkout.delivery_time', 
                'checkout.payment_method'
            ]);
            
            // 注文受付メールを送信
            try {
                $order->load(['user', 'items.product', 'shippingAddress', 'bankTransfer']);
                $order->user->notify(new OrderReceivedNotification($order));
            } catch (\Exception $e) {
                Log::error('注文受付メール送信エラー: ' . $e->getMessage());
                // メール送信エラーは注文処理を止めない
            }
            
            // トランザクションコミット
            DB::commit();
            
            // 支払い方法に応じたリダイレクト
            if ($paymentMethod === Order::PAYMENT_METHOD_STRIPE) {
                // Stripe決済画面にリダイレクト
                return redirect($order->stripe_session_url);
            } else {
                // 注文完了画面にリダイレクト
                return redirect()->route('checkout.complete', ['order' => $order->id]);
            }
            
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
     * Stripe決済処理
     */
    private function processStripePayment(Order $order, Cart $cart, float $subtotal, float $tax, float $shippingFee)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        // Checkout Sessionの作成
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
        $order->stripe_session_url = $session->url;
        $order->save();
    }

    /**
     * 銀行振込情報の作成
     */
    private function createBankTransfer(Order $order, float $total)
    {
        // 管理画面で設定した銀行振込情報を取得
        $paymentMethod = PaymentMethod::where('key', PaymentMethod::BANK_TRANSFER)->first();
        
        if (!$paymentMethod || !$paymentMethod->is_enabled) {
            throw new \Exception('銀行振込決済が有効になっていません。');
        }
        
        $settings = $paymentMethod->settings;
        
        // 必要な設定が不足している場合はエラー
        $requiredFields = ['bank_name', 'bank_branch', 'account_type', 'account_number', 'account_name'];
        foreach ($requiredFields as $field) {
            if (empty($settings[$field])) {
                throw new \Exception("銀行振込設定が不完全です。管理画面で{$field}を設定してください。");
            }
        }
        
        // 振込期限の計算
        $deadlineDays = $settings['deadline_days'] ?? 7;
        $transferDeadline = $order->transfer_deadline ?: Carbon::now()->addDays($deadlineDays);
        
        $bankTransfer = new BankTransfer([
            'order_id' => $order->id,
            'bank_name' => $settings['bank_name'],
            'branch_name' => $settings['bank_branch'],
            'account_type' => $settings['account_type'],
            'account_number' => $settings['account_number'],
            'account_holder' => $settings['account_name'],
            'transfer_amount' => $total,
            'transfer_deadline' => $transferDeadline,
            'transfer_status' => BankTransfer::STATUS_PENDING,
        ]);
        $bankTransfer->save();
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
        
        // 銀行振込情報をロード
        $order->load('bankTransfer');
        
        // Stripe決済の場合は支払い状態を確認（実際にはWebhookで処理するのが望ましい）
        if ($order->isStripePayment()) {
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
        }
        
        return view('checkout.complete', compact('order'));
    }
}
