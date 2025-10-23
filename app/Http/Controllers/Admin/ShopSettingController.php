<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopSetting;
use App\Models\PaymentMethod;
use App\Services\ShopSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ShopSettingController extends Controller
{
    /**
     * ショップ設定一覧画面
     */
    public function index()
    {
        $basicInfo = ShopSettingService::getBasicInfo();
        $shippingSettings = ShopSettingService::getShippingSettings();
        $paymentSettings = ShopSettingService::getPaymentSettings();
        $legalInfo = ShopSettingService::getLegalInfo();

        return view('admin.shop-settings.index', compact(
            'basicInfo',
            'shippingSettings', 
            'paymentSettings',
            'legalInfo'
        ));
    }

    /**
     * 基本情報設定画面
     */
    public function basicInfo()
    {
        $settings = ShopSettingService::getBasicInfo();
        return view('admin.shop-settings.basic-info', compact('settings'));
    }

    /**
     * 基本情報更新
     */
    public function updateBasicInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shop_name' => 'required|string|max:255',
            'shop_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_name' => 'nullable|string|max:255',
            'company_name_kana' => 'nullable|string|max:255',
            'shop_name_kana' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'prefecture' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'address_line' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'business_hours' => 'nullable|string|max:255',
            'shop_description' => 'nullable|string|max:1000',
            'free_shipping_amount' => 'nullable|integer|min:0',
            'free_shipping_quantity' => 'nullable|integer|min:0',
            'invoice_registration_number' => 'nullable|string|max:255',
            'point_rate' => 'nullable|string|max:10',
            'point_conversion_rate' => 'nullable|string|max:10',
            'google_analytics_tracking_id' => 'nullable|string|max:255',
            'tax_rate' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            // ロゴファイルのアップロード処理
            $logoPath = '';
            if ($request->hasFile('shop_logo')) {
                $logoPath = $request->file('shop_logo')->store('shop/logos', 'public');
            } else {
                // 既存のロゴパスを保持
                $logoPath = ShopSetting::getValue('basic_info.shop_logo', '');
            }

            $basicInfoSettings = [
                // 店舗情報
                'company_name' => $request->company_name ?? '',
                'company_name_kana' => $request->company_name_kana ?? '',
                'shop_name' => $request->shop_name,
                'shop_name_kana' => $request->shop_name_kana ?? '',
                'shop_logo' => $logoPath,
                'postal_code' => $request->postal_code ?? '',
                'prefecture' => $request->prefecture ?? '',
                'city' => $request->city ?? '',
                'address_line' => $request->address_line ?? '',
                'phone_number' => $request->phone_number ?? '',
                'business_hours' => $request->business_hours ?? '',
                'shop_description' => $request->shop_description ?? '',
                
                // 会員設定
                'guest_purchase_enabled' => $request->guest_purchase_enabled === '1',
                'favorite_enabled' => $request->favorite_enabled === '1',
                
                // 商品設定
                'show_out_of_stock' => $request->show_out_of_stock === '1',
                
                // 送料設定
                'free_shipping_amount' => (int) ($request->free_shipping_amount ?? 0),
                'free_shipping_quantity' => (int) ($request->free_shipping_quantity ?? 0),
                
                // 税設定
                'invoice_registration_number' => $request->invoice_registration_number ?? '',
                
                // ポイント設定
                'point_enabled' => $request->point_enabled === '1',
                'point_rate' => $request->point_rate ?? '1',
                'point_conversion_rate' => $request->point_conversion_rate ?? '1',
                
                // Googleアナリティクス設定
                'google_analytics_tracking_id' => $request->google_analytics_tracking_id ?? '',
            ];

            $paymentSettings = [
                'tax_rate' => (int) $request->tax_rate,
                'tax_included' => $request->has('tax_included'),
            ];

            // 基本情報設定を更新
            ShopSettingService::updateSettings('basic_info', $basicInfoSettings);
            
            // 決済設定を更新
            ShopSettingService::updateSettings('payment', $paymentSettings);

            DB::commit();

            return redirect()->route('admin.shop-settings.basic-info')
                           ->with('success', '基本情報を更新しました。');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', '更新に失敗しました: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * 配送設定画面
     */
    public function shipping()
    {
        $shippingCompanies = \App\Models\ShippingCompany::ordered()->get();
        $paymentMethods = \App\Models\ShippingCompany::getPaymentMethods();
        $prefectures = \App\Models\ShippingCompany::getPrefectures();
        
        return view('admin.shop-settings.shipping', compact('shippingCompanies', 'paymentMethods', 'prefectures'));
    }

    /**
     * 配送業者作成・更新
     */
    public function updateShipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'method_name' => 'required|string|max:255',
            'payment_methods' => 'nullable|array',
            'delivery_times' => 'nullable|array',
            'uniform_shipping_fee' => 'required|in:0,1',
            'uniform_fee' => 'nullable|integer|min:0',
            'prefecture_fees' => 'nullable|array',
            'prefecture_fees.*' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:2000',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $data = [
            'name' => $request->name,
            'method_name' => $request->method_name,
            'payment_methods' => $request->payment_methods ?? [],
            'delivery_times' => $request->delivery_times ?? [],
            'uniform_shipping_fee' => $request->uniform_shipping_fee === '1',
            'uniform_fee' => $request->uniform_shipping_fee === '1' ? $request->uniform_fee : null,
            'prefecture_fees' => $request->uniform_shipping_fee === '0' ? $request->prefecture_fees : null,
            'notes' => $request->notes ?? '',
            'is_active' => $request->has('is_active') && $request->is_active,
        ];

        if ($request->has('id') && $request->id) {
            // 更新
            $shippingCompany = \App\Models\ShippingCompany::findOrFail($request->id);
            $shippingCompany->update($data);
            $message = '配送業者を更新しました。';
        } else {
            // 新規作成
            \App\Models\ShippingCompany::create($data);
            $message = '配送業者を作成しました。';
        }

        return redirect()->route('admin.shop-settings.shipping')
                         ->with('success', $message);
    }

    /**
     * 配送業者削除
     */
    public function deleteShipping(Request $request)
    {
        $shippingCompany = \App\Models\ShippingCompany::findOrFail($request->id);
        $shippingCompany->delete();

        return redirect()->route('admin.shop-settings.shipping')
                         ->with('success', '配送業者を削除しました。');
    }

    /**
     * 配送業者有効/無効切り替え
     */
    public function toggleShippingStatus(Request $request)
    {
        $shippingCompany = \App\Models\ShippingCompany::findOrFail($request->id);
        $shippingCompany->is_active = !$shippingCompany->is_active;
        $shippingCompany->save();

        $status = $shippingCompany->is_active ? '有効' : '無効';
        return redirect()->route('admin.shop-settings.shipping')
                         ->with('success', "配送業者を{$status}に変更しました。");
    }

    /**
     * 配送業者作成画面
     */
    public function createShipping()
    {
        $paymentMethods = \App\Models\ShippingCompany::getPaymentMethods();
        $prefectures = \App\Models\ShippingCompany::getPrefectures();
        
        return view('admin.shop-settings.shipping-create', compact('paymentMethods', 'prefectures'));
    }

    /**
     * 配送業者編集画面
     */
    public function editShipping($id)
    {
        $shippingCompany = \App\Models\ShippingCompany::findOrFail($id);
        $paymentMethods = \App\Models\ShippingCompany::getPaymentMethods();
        $prefectures = \App\Models\ShippingCompany::getPrefectures();
        
        return view('admin.shop-settings.shipping-edit', compact('shippingCompany', 'paymentMethods', 'prefectures'));
    }

    /**
     * 特定商取引法表記設定画面
     */
    public function legal()
    {
        $settings = ShopSettingService::getLegalInfo();
        $basicInfo = ShopSettingService::getBasicInfo();
        
        // 販売業者名が空の場合、基本情報の事業者名をデフォルト値として使用
        if (empty($settings['company_name']) && !empty($basicInfo['company_name'])) {
            $settings['company_name'] = $basicInfo['company_name'];
        }
        
        return view('admin.shop-settings.legal', compact('settings'));
    }

    /**
     * 特定商取引法表記更新
     */
    public function updateLegal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'representative_name' => 'required|string|max:255',
            'company_phone' => 'required|string|max:50',
            'company_address' => 'required|string|max:500',
            'additional_charges' => 'nullable|string|max:1000',
            'payment_timing' => 'nullable|string|max:1000',
            'delivery_timing' => 'nullable|string|max:1000',
            'return_policy' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $settings = [
            'company_name' => $request->company_name,
            'representative_name' => $request->representative_name,
            'company_phone' => $request->company_phone,
            'company_address' => $request->company_address,
            'additional_charges' => $request->additional_charges ?? '',
            'payment_timing' => $request->payment_timing ?? '',
            'delivery_timing' => $request->delivery_timing ?? '',
            'return_policy' => $request->return_policy ?? '',
        ];

        ShopSettingService::updateSettings('legal', $settings);

        return redirect()->route('admin.shop-settings.legal')
                         ->with('success', '特定商取引法表記を更新しました。');
    }

    /**
     * プライバシーポリシー設定画面
     */
    public function privacy()
    {
        $settings = ShopSettingService::getPrivacyPolicy();
        $basicInfo = ShopSettingService::getBasicInfo();
        
        // 事業者名が空の場合、基本情報の事業者名をデフォルト値として使用
        if (empty($settings['privacy_company_name']) && !empty($basicInfo['company_name'])) {
            $settings['privacy_company_name'] = $basicInfo['company_name'];
        }
        
        return view('admin.shop-settings.privacy', compact('settings'));
    }

    /**
     * プライバシーポリシー更新
     */
    public function updatePrivacy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'privacy_company_name' => 'required|string|max:255',
            'privacy_updated_date' => 'required|date',
            'collection_purpose' => 'required|string|max:2000',
            'collected_information' => 'required|string|max:1000',
            'third_party_provision' => 'required|string|max:1000',
            'information_management' => 'required|string|max:1000',
            'customer_rights' => 'required|string|max:1000',
            'cookie_policy' => 'nullable|string|max:1000',
            'contact_information' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $settings = [
            'privacy_company_name' => $request->privacy_company_name,
            'privacy_updated_date' => $request->privacy_updated_date,
            'collection_purpose' => $request->collection_purpose,
            'collected_information' => $request->collected_information,
            'third_party_provision' => $request->third_party_provision,
            'information_management' => $request->information_management,
            'customer_rights' => $request->customer_rights,
            'cookie_policy' => $request->cookie_policy ?? '',
            'contact_information' => $request->contact_information,
        ];

        ShopSettingService::updateSettings('privacy', $settings);

        return redirect()->route('admin.shop-settings.privacy')
                         ->with('success', 'プライバシーポリシーを更新しました。');
    }

    /**
     * 決済設定画面
     */
    public function payment()
    {
        // PaymentMethodのデフォルト設定を作成（存在しない場合）
        PaymentMethod::createDefaults();
        
        $paymentMethods = PaymentMethod::getAllOrdered();
        
        return view('admin.shop-settings.payment', compact('paymentMethods'));
    }

    /**
     * 決済方法の有効/無効切り替え（Ajax）
     */
    public function togglePaymentMethod(Request $request)
    {
        try {
            // バリデーション
            $request->validate([
                'method_key' => 'required|string',
                'is_enabled' => 'required'
            ]);

            // 決済方法を取得
            $paymentMethod = PaymentMethod::where('key', $request->method_key)->first();
            
            if (!$paymentMethod) {
                return response()->json([
                    'success' => false, 
                    'message' => '決済方法が見つかりません'
                ], 404);
            }

            // 新しい状態を設定
            $newState = $request->is_enabled == 1 || $request->is_enabled === true || $request->is_enabled === 'true';

            // データベースを更新
            $paymentMethod->is_enabled = $newState;
            $saved = $paymentMethod->save();

            if (!$saved) {
                return response()->json([
                    'success' => false,
                    'message' => 'データベースの更新に失敗しました'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'method_key' => $paymentMethod->key,
                'is_enabled' => $paymentMethod->is_enabled
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'サーバーエラーが発生しました'
            ], 500);
        }
    }

    /**
     * 決済方法設定画面
     */
    public function paymentMethod($method)
    {
        $paymentMethod = PaymentMethod::getByKey($method);
        
        if (!$paymentMethod) {
            return redirect()->route('admin.shop-settings.payment')
                           ->with('error', '指定された決済方法が見つかりません。');
        }

        $viewName = "admin.shop-settings.payment.{$method}";
        
        if (!view()->exists($viewName)) {
            return redirect()->route('admin.shop-settings.payment')
                           ->with('error', '設定画面が見つかりません。');
        }

        return view($viewName, compact('paymentMethod'));
    }

    /**
     * 決済方法設定更新
     */
    public function updatePaymentMethod(Request $request, $method)
    {
        try {
            // デバッグ: メソッドが呼ばれたことを確認
            logger('updatePaymentMethod called', [
                'method' => $method,
                'request_method' => $request->method(),
                'all_input' => $request->all()
            ]);
            
            $paymentMethod = PaymentMethod::getByKey($method);
            logger('PaymentMethod retrieved', ['found' => !is_null($paymentMethod)]);
            
            if (!$paymentMethod) {
                logger('PaymentMethod not found', ['method' => $method]);
                return redirect()->route('admin.shop-settings.payment')
                               ->with('error', '指定された決済方法が見つかりません。');
            }

            // 決済方法別のバリデーション
            $rules = $this->getValidationRules($method);
            logger('Validation rules', ['rules' => $rules]);
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                logger('Validation failed', ['errors' => $validator->errors()->toArray()]);
                return redirect()->back()
                               ->withErrors($validator)
                               ->withInput();
            }
            
            logger('Validation passed');

        // 代金引換の場合、レンジの重複チェック
        if ($method === PaymentMethod::CASH_ON_DELIVERY && $request->has('settings.cod_fee_ranges')) {
            $rangeValidation = $this->validateCodFeeRanges($request->input('settings.cod_fee_ranges', []));
            if (!$rangeValidation['valid']) {
                return redirect()->back()
                               ->withErrors(['settings.cod_fee_ranges' => $rangeValidation['message']])
                               ->withInput();
            }
        }

        // 基本設定の更新
        $paymentMethod->is_enabled = $request->has('is_enabled');
        
        // 代金引換の場合、レンジの重複チェック
        if ($method === PaymentMethod::CASH_ON_DELIVERY && $request->has('settings.cod_fee_ranges')) {
            $rangeValidation = $this->validateCodFeeRanges($request->input('settings.cod_fee_ranges', []));
            if (!$rangeValidation['valid']) {
                return redirect()->back()
                               ->withErrors(['settings.cod_fee_ranges' => $rangeValidation['message']])
                               ->withInput();
            }
        }

        // 設定データの更新
        $settings = $paymentMethod->settings ?? [];
        
        if ($request->has('settings')) {
            foreach ($request->settings as $key => $value) {
                $settings[$key] = $value;
            }
        }
        
        // デバッグログ追加
        logger('Updating payment method settings', [
            'method' => $method,
            'request_settings' => $request->input('settings'),
            'merged_settings' => $settings,
            'current_settings' => $paymentMethod->settings
        ]);
        
        $paymentMethod->settings = $settings;
        $saved = $paymentMethod->save();
        
        // 保存後の確認
        logger('Payment method saved', [
            'saved' => $saved,
            'final_settings' => $paymentMethod->fresh()->settings
        ]);

        return redirect()->route('admin.shop-settings.payment.method', $method)
                         ->with('success', $paymentMethod->name . 'の設定を更新しました。');
        
        } catch (\Exception $e) {
            logger('Exception in updatePaymentMethod', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                           ->with('error', 'エラーが発生しました: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * 代引手数料レンジのバリデーション
     */
    private function validateCodFeeRanges($ranges)
    {
        if (empty($ranges)) {
            return ['valid' => true];
        }

        // レンジをソート
        usort($ranges, function($a, $b) {
            return $a['min_amount'] - $b['min_amount'];
        });

        $previousMax = -1;
        
        foreach ($ranges as $index => $range) {
            $minAmount = (int)$range['min_amount'];
            $maxAmount = isset($range['max_amount']) && $range['max_amount'] !== '' ? (int)$range['max_amount'] : null;
            
            // 最小金額が前のレンジの最大金額より小さい場合はエラー
            if ($minAmount <= $previousMax) {
                return [
                    'valid' => false,
                    'message' => '金額レンジが重複しています。レンジ' . ($index + 1) . 'の最小金額を確認してください。'
                ];
            }
            
            // 最大金額が設定されている場合、最小金額より大きいかチェック
            if ($maxAmount !== null && $maxAmount <= $minAmount) {
                return [
                    'valid' => false,
                    'message' => 'レンジ' . ($index + 1) . 'の最大金額は最小金額より大きい値を設定してください。'
                ];
            }
            
            $previousMax = $maxAmount ?? PHP_INT_MAX;
        }
        
        return ['valid' => true];
    }

    /**
     * 決済方法別のバリデーションルールを取得
     */
    private function getValidationRules($method)
    {
        switch ($method) {
            case PaymentMethod::STRIPE:
                return [
                    'is_enabled' => 'nullable|in:on,1,true',
                    'settings.test_mode' => 'nullable|in:on,1,true',
                    'settings.fee_rate' => 'nullable|numeric|min:0|max:100',
                ];
                
            case PaymentMethod::BANK_TRANSFER:
                return [
                    'is_enabled' => 'nullable|in:on,1,true',
                    'settings.deadline_days' => 'required|integer|min:1|max:30',
                    'settings.transfer_fee' => 'nullable|integer|min:0',
                    'settings.bank_name' => 'required|string|max:255',
                    'settings.bank_branch' => 'required|string|max:255',
                    'settings.account_type' => 'required|string|max:50',
                    'settings.account_number' => 'required|string|max:255',
                    'settings.account_name' => 'required|string|max:255',
                    'settings.notes' => 'nullable|string|max:2000',
                ];
                
            case PaymentMethod::CASH_ON_DELIVERY:
                return [
                    'is_enabled' => 'nullable|in:on,1,true',
                    'settings.cod_fee_ranges' => 'nullable|array',
                    'settings.cod_fee_ranges.*.min_amount' => 'required|integer|min:0',
                    'settings.cod_fee_ranges.*.max_amount' => 'nullable|integer|min:0',
                    'settings.cod_fee_ranges.*.fee' => 'required|integer|min:0',
                    'settings.min_amount' => 'nullable|integer|min:0',
                    'settings.max_amount' => 'nullable|integer|min:0',
                    'settings.delivery_company' => 'nullable|string|max:255',
                    'settings.notes' => 'nullable|string|max:2000',
                    'settings.allow_remote_islands' => 'nullable|in:on,1,true',
                    'settings.allow_time_designation' => 'nullable|in:on,1,true',
                ];
                
            default:
                return [
                    'is_enabled' => 'nullable|in:on,1,true',
                ];
        }
    }
}
