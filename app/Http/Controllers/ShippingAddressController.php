<?php

namespace App\Http\Controllers;

use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShippingAddressController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 配送先住所一覧を表示
     */
    public function index()
    {
        $addresses = Auth::user()->shippingAddresses()->orderBy('is_default', 'desc')->get();
        return view('shipping_addresses.index', compact('addresses'));
    }

    /**
     * 配送先住所作成フォームを表示
     */
    public function create()
    {
        return view('shipping_addresses.create');
    }

    /**
     * 配送先住所を保存
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:8',
            'prefecture' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $isDefault = $request->has('is_default');
        
        // もしデフォルトに設定する場合は、他のアドレスをデフォルトから外す
        if ($isDefault) {
            Auth::user()->shippingAddresses()->update(['is_default' => false]);
        }
        
        // 初めての住所登録の場合は自動的にデフォルトにする
        $hasAddresses = Auth::user()->shippingAddresses()->exists();
        if (!$hasAddresses) {
            $isDefault = true;
        }

        $address = new ShippingAddress($request->all());
        $address->user_id = Auth::id();
        $address->is_default = $isDefault;
        $address->save();

        return redirect()->route('shipping_addresses.index')
            ->with('success', '配送先住所を登録しました。');
    }

    /**
     * 配送先住所編集フォームを表示
     */
    public function edit(ShippingAddress $shippingAddress)
    {
        // 自分の住所のみ編集可能
        if ($shippingAddress->user_id !== Auth::id()) {
            abort(403);
        }

        return view('shipping_addresses.edit', compact('shippingAddress'));
    }

    /**
     * 配送先住所を更新
     */
    public function update(Request $request, ShippingAddress $shippingAddress)
    {
        // 自分の住所のみ更新可能
        if ($shippingAddress->user_id !== Auth::id()) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:8',
            'prefecture' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $isDefault = $request->has('is_default');
        
        // もしデフォルトに設定する場合は、他のアドレスをデフォルトから外す
        if ($isDefault && !$shippingAddress->is_default) {
            Auth::user()->shippingAddresses()->where('id', '!=', $shippingAddress->id)
                ->update(['is_default' => false]);
        }

        $shippingAddress->fill($request->all());
        $shippingAddress->is_default = $isDefault;
        $shippingAddress->save();

        return redirect()->route('shipping_addresses.index')
            ->with('success', '配送先住所を更新しました。');
    }

    /**
     * 配送先住所を削除
     */
    public function destroy(ShippingAddress $shippingAddress)
    {
        // 自分の住所のみ削除可能
        if ($shippingAddress->user_id !== Auth::id()) {
            abort(403);
        }

        // デフォルト住所は削除できない場合の処理
        if ($shippingAddress->is_default && Auth::user()->shippingAddresses()->count() > 1) {
            return redirect()->route('shipping_addresses.index')
                ->with('error', 'デフォルト住所は削除できません。先に別の住所をデフォルトに設定してください。');
        }

        $shippingAddress->delete();

        // 削除後に他の住所があれば、一つをデフォルトに設定
        if (Auth::user()->shippingAddresses()->count() > 0 && $shippingAddress->is_default) {
            $newDefault = Auth::user()->shippingAddresses()->first();
            $newDefault->is_default = true;
            $newDefault->save();
        }

        return redirect()->route('shipping_addresses.index')
            ->with('success', '配送先住所を削除しました。');
    }

    /**
     * 配送先住所をデフォルトに設定
     */
    public function setDefault(ShippingAddress $shippingAddress)
    {
        // 自分の住所のみ操作可能
        if ($shippingAddress->user_id !== Auth::id()) {
            abort(403);
        }

        // 現在のデフォルトアドレスをリセット
        Auth::user()->shippingAddresses()->update(['is_default' => false]);

        // 選択したアドレスをデフォルトに設定
        $shippingAddress->is_default = true;
        $shippingAddress->save();

        return redirect()->route('shipping_addresses.index')
            ->with('success', 'デフォルト配送先を変更しました。');
    }
}
