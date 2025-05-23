<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * カートを表示
     */
    public function index()
    {
        $cart = $this->getCart();
        
        return view('cart.index', compact('cart'));
    }
    
    /**
     * 商品をカートに追加
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $cart = $this->getCart();
        $product = Product::findOrFail($request->product_id);
        
        // 在庫チェック
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', '商品の在庫が不足しています。');
        }
        
        // 同じ商品がすでにカートにあるかチェック
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            // 既存のアイテムの数量を更新
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
            ]);
        } else {
            // 新しいアイテムをカートに追加
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->getCurrentPrice(),
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', '商品をカートに追加しました。');
    }
    
    /**
     * カート内の商品を更新
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        // カートの所有者チェック
        if ($cartItem->cart_id !== $this->getCart()->id) {
            abort(403);
        }
        
        // 在庫チェック
        $product = $cartItem->product;
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', '商品の在庫が不足しています。');
        }
        
        // 数量を更新
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);
        
        return redirect()->route('cart.index')->with('success', 'カートを更新しました。');
    }
    
    /**
     * カートから商品を削除
     */
    public function remove(CartItem $cartItem)
    {
        // カートの所有者チェック
        if ($cartItem->cart_id !== $this->getCart()->id) {
            abort(403);
        }
        
        $cartItem->delete();
        
        return redirect()->route('cart.index')->with('success', '商品をカートから削除しました。');
    }
    
    /**
     * カートを空にする
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        
        return redirect()->route('cart.index')->with('success', 'カートを空にしました。');
    }
    
    /**
     * 現在のユーザーまたはセッションのカートを取得または作成
     */
    protected function getCart()
    {
        if (Auth::check()) {
            // ログインユーザーの場合
            $cart = Auth::user()->cart;
            
            if (!$cart) {
                // カートがない場合は新規作成
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                ]);
                
                // セッションにカートがある場合はマージ
                $sessionId = session('cart_session_id');
                if ($sessionId) {
                    $sessionCart = Cart::where('session_id', $sessionId)->first();
                    if ($sessionCart) {
                        // セッションカートの商品をユーザーカートに移動
                        foreach ($sessionCart->items as $item) {
                            $existingItem = $cart->items()->where('product_id', $item->product_id)->first();
                            
                            if ($existingItem) {
                                // 既存のアイテムの数量を更新
                                $existingItem->update([
                                    'quantity' => $existingItem->quantity + $item->quantity,
                                ]);
                            } else {
                                // 新しいアイテムをカートに追加
                                $cart->items()->create([
                                    'product_id' => $item->product_id,
                                    'quantity' => $item->quantity,
                                    'price' => $item->price,
                                ]);
                            }
                        }
                        
                        // セッションカートを削除
                        $sessionCart->items()->delete();
                        $sessionCart->delete();
                        session()->forget('cart_session_id');
                    }
                }
            }
            
            return $cart;
        } else {
            // 非ログインユーザーの場合
            $sessionId = session('cart_session_id');
            
            if (!$sessionId) {
                // 新しいセッションIDを生成
                $sessionId = Str::uuid();
                session(['cart_session_id' => $sessionId]);
            }
            
            // セッションIDでカートを検索
            $cart = Cart::where('session_id', $sessionId)->first();
            
            if (!$cart) {
                // カートがない場合は新規作成
                $cart = Cart::create([
                    'session_id' => $sessionId,
                ]);
            }
            
            return $cart;
        }
    }
}
