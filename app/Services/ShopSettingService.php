<?php

namespace App\Services;

use App\Models\ShopSetting;

class ShopSettingService
{
    /**
     * ショップ基本情報を取得
     */
    public static function getBasicInfo()
    {
        return [
            // 店舗情報
            'company_name' => ShopSetting::getValue('basic_info.company_name', ''),
            'company_name_kana' => ShopSetting::getValue('basic_info.company_name_kana', ''),
            'shop_name' => ShopSetting::getValue('basic_info.shop_name', 'ECショップ'),
            'shop_name_kana' => ShopSetting::getValue('basic_info.shop_name_kana', ''),
            'shop_logo' => ShopSetting::getValue('basic_info.shop_logo', ''),
            'postal_code' => ShopSetting::getValue('basic_info.postal_code', ''),
            'prefecture' => ShopSetting::getValue('basic_info.prefecture', ''),
            'city' => ShopSetting::getValue('basic_info.city', ''),
            'address_line' => ShopSetting::getValue('basic_info.address_line', ''),
            'phone_number' => ShopSetting::getValue('basic_info.phone_number', ''),
            'business_hours' => ShopSetting::getValue('basic_info.business_hours', ''),
            'shop_description' => ShopSetting::getValue('basic_info.shop_description', ''),
            
            // 会員設定
            'guest_purchase_enabled' => ShopSetting::getValue('basic_info.guest_purchase_enabled', true, ShopSetting::TYPE_BOOLEAN),
            'favorite_enabled' => ShopSetting::getValue('basic_info.favorite_enabled', true, ShopSetting::TYPE_BOOLEAN),
            
            // 商品設定
            'show_out_of_stock' => ShopSetting::getValue('basic_info.show_out_of_stock', true, ShopSetting::TYPE_BOOLEAN),
            
            // 送料設定
            'free_shipping_amount' => ShopSetting::getValue('basic_info.free_shipping_amount', 0, ShopSetting::TYPE_INTEGER),
            'free_shipping_quantity' => ShopSetting::getValue('basic_info.free_shipping_quantity', 0, ShopSetting::TYPE_INTEGER),
            
            // 税設定
            'invoice_registration_number' => ShopSetting::getValue('basic_info.invoice_registration_number', ''),
            
            // ポイント設定
            'point_enabled' => ShopSetting::getValue('basic_info.point_enabled', false, ShopSetting::TYPE_BOOLEAN),
            'point_rate' => ShopSetting::getValue('basic_info.point_rate', '1'),
            'point_conversion_rate' => ShopSetting::getValue('basic_info.point_conversion_rate', '1'),
            
            // Googleアナリティクス設定
            'google_analytics_tracking_id' => ShopSetting::getValue('basic_info.google_analytics_tracking_id', ''),
        ];
    }

    /**
     * 配送設定を取得
     */
    public static function getShippingSettings()
    {
        return [
            'shipping_fee' => ShopSetting::getValue('shipping.shipping_fee', 500, ShopSetting::TYPE_INTEGER),
            'free_shipping_threshold' => ShopSetting::getValue('shipping.free_shipping_threshold', 5000, ShopSetting::TYPE_INTEGER),
            'shipping_days' => ShopSetting::getValue('shipping.shipping_days', 3, ShopSetting::TYPE_INTEGER),
            'shipping_company' => ShopSetting::getValue('shipping.shipping_company', 'ヤマト運輸'),
            'time_slots' => ShopSetting::getValue('shipping.time_slots', [], ShopSetting::TYPE_JSON),
            'excluded_dates' => ShopSetting::getValue('shipping.excluded_dates', [], ShopSetting::TYPE_JSON),
        ];
    }

    /**
     * 決済設定を取得
     */
    public static function getPaymentSettings()
    {
        return [
            'stripe_enabled' => ShopSetting::getValue('payment.stripe_enabled', true, ShopSetting::TYPE_BOOLEAN),
            'bank_transfer_enabled' => ShopSetting::getValue('payment.bank_transfer_enabled', true, ShopSetting::TYPE_BOOLEAN),
            'tax_rate' => ShopSetting::getValue('payment.tax_rate', 10, ShopSetting::TYPE_INTEGER),
            'tax_included' => ShopSetting::getValue('payment.tax_included', true, ShopSetting::TYPE_BOOLEAN),
            'bank_transfer_deadline_days' => ShopSetting::getValue('payment.bank_transfer_deadline_days', 7, ShopSetting::TYPE_INTEGER),
            'bank_name' => ShopSetting::getValue('payment.bank_name', ''),
            'bank_branch' => ShopSetting::getValue('payment.bank_branch', ''),
            'account_type' => ShopSetting::getValue('payment.account_type', ''),
            'account_number' => ShopSetting::getValue('payment.account_number', ''),
            'account_name' => ShopSetting::getValue('payment.account_name', ''),
        ];
    }

    /**
     * 法的表記を取得
     */
    public static function getLegalInfo()
    {
        return [
            'company_name' => ShopSetting::getValue('legal.company_name', ''),
            'representative_name' => ShopSetting::getValue('legal.representative_name', ''),
            'company_phone' => ShopSetting::getValue('legal.company_phone', ''),
            'company_address' => ShopSetting::getValue('legal.company_address', ''),
            'additional_charges' => ShopSetting::getValue('legal.additional_charges', ''),
            'payment_timing' => ShopSetting::getValue('legal.payment_timing', ''),
            'delivery_timing' => ShopSetting::getValue('legal.delivery_timing', ''),
            'return_policy' => ShopSetting::getValue('legal.return_policy', ''),
        ];
    }

    /**
     * プライバシーポリシーを取得
     */
    public static function getPrivacyPolicy()
    {
        return [
            'privacy_company_name' => ShopSetting::getValue('privacy.privacy_company_name', ''),
            'privacy_updated_date' => ShopSetting::getValue('privacy.privacy_updated_date', ''),
            'collection_purpose' => ShopSetting::getValue('privacy.collection_purpose', ''),
            'collected_information' => ShopSetting::getValue('privacy.collected_information', ''),
            'third_party_provision' => ShopSetting::getValue('privacy.third_party_provision', ''),
            'information_management' => ShopSetting::getValue('privacy.information_management', ''),
            'customer_rights' => ShopSetting::getValue('privacy.customer_rights', ''),
            'cookie_policy' => ShopSetting::getValue('privacy.cookie_policy', ''),
            'contact_information' => ShopSetting::getValue('privacy.contact_information', ''),
        ];
    }

    /**
     * ショップロゴまたはショップ名を取得（ロゴが設定されていればロゴ、なければショップ名）
     */
    public static function getShopLogoOrName()
    {
        $basicInfo = self::getBasicInfo();
        $logo = $basicInfo['shop_logo'];
        $shopName = $basicInfo['shop_name'];
        
        if (!empty($logo) && file_exists(public_path('storage/' . $logo))) {
            return [
                'type' => 'logo',
                'value' => asset('storage/' . $logo),
                'alt' => $shopName
            ];
        }
        
        return [
            'type' => 'text',
            'value' => $shopName,
            'alt' => $shopName
        ];
    }

    /**
     * 設定値を一括更新
     */
    public static function updateSettings($group, $settings)
    {
        foreach ($settings as $key => $value) {
            $fullKey = "{$group}.{$key}";
            
            // データ型を推定
            $type = ShopSetting::TYPE_STRING;
            if (is_bool($value)) {
                $type = ShopSetting::TYPE_BOOLEAN;
            } elseif (is_int($value)) {
                $type = ShopSetting::TYPE_INTEGER;
            } elseif (is_array($value)) {
                $type = ShopSetting::TYPE_JSON;
            }
            
            ShopSetting::setValue($fullKey, $value, $type, $group);
        }
    }

    /**
     * 初期設定データを作成
     */
    public static function seedDefaultSettings()
    {
        $defaults = [
            // 基本情報 - 店舗情報
            'basic_info.company_name' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '事業者名'],
            'basic_info.company_name_kana' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '事業者名(カナ)'],
            'basic_info.shop_name' => ['value' => 'ECショップ', 'type' => ShopSetting::TYPE_STRING, 'description' => 'ショップ名'],
            'basic_info.shop_name_kana' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => 'ショップ名(カナ)'],
            'basic_info.shop_logo' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => 'ショップロゴ'],
            'basic_info.postal_code' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '郵便番号'],
            'basic_info.prefecture' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '都道府県'],
            'basic_info.city' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '市区町村名'],
            'basic_info.address_line' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '番地・ビル名'],
            'basic_info.phone_number' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '電話番号'],
            'basic_info.business_hours' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '店舗営業時間'],
            'basic_info.shop_description' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => 'ショップ説明文'],
            
            // 基本情報 - 会員設定
            'basic_info.guest_purchase_enabled' => ['value' => '1', 'type' => ShopSetting::TYPE_BOOLEAN, 'description' => 'ゲスト購入'],
            'basic_info.favorite_enabled' => ['value' => '1', 'type' => ShopSetting::TYPE_BOOLEAN, 'description' => 'お気に入り商品'],
            
            // 基本情報 - 商品設定
            'basic_info.show_out_of_stock' => ['value' => '1', 'type' => ShopSetting::TYPE_BOOLEAN, 'description' => '在庫切れ商品の表示'],
            
            // 基本情報 - 送料設定
            'basic_info.free_shipping_amount' => ['value' => '0', 'type' => ShopSetting::TYPE_INTEGER, 'description' => '送料無料金額'],
            'basic_info.free_shipping_quantity' => ['value' => '0', 'type' => ShopSetting::TYPE_INTEGER, 'description' => '送料無料数量'],
            
            // 基本情報 - 税設定
            'basic_info.invoice_registration_number' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '適格請求書発行事業者登録番号'],
            
            // 基本情報 - ポイント設定
            'basic_info.point_enabled' => ['value' => '0', 'type' => ShopSetting::TYPE_BOOLEAN, 'description' => 'ポイント機能'],
            'basic_info.point_rate' => ['value' => '1', 'type' => ShopSetting::TYPE_STRING, 'description' => 'ポイント付与率'],
            'basic_info.point_conversion_rate' => ['value' => '1', 'type' => ShopSetting::TYPE_STRING, 'description' => 'ポイント換算レート'],
            
            // 基本情報 - Googleアナリティクス設定
            'basic_info.google_analytics_tracking_id' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => 'トラッキングID'],
            
            // 配送設定
            'shipping.shipping_fee' => ['value' => '500', 'type' => ShopSetting::TYPE_INTEGER, 'description' => '配送料金'],
            'shipping.free_shipping_threshold' => ['value' => '5000', 'type' => ShopSetting::TYPE_INTEGER, 'description' => '送料無料条件（円）'],
            'shipping.shipping_days' => ['value' => '3', 'type' => ShopSetting::TYPE_INTEGER, 'description' => '配送日数'],
            'shipping.shipping_company' => ['value' => 'ヤマト運輸', 'type' => ShopSetting::TYPE_STRING, 'description' => '配送業者'],
            'shipping.time_slots' => ['value' => json_encode([]), 'type' => ShopSetting::TYPE_JSON, 'description' => '配送時間帯'],
            'shipping.excluded_dates' => ['value' => json_encode([]), 'type' => ShopSetting::TYPE_JSON, 'description' => '配送除外日'],
            
            // 決済設定
            'payment.stripe_enabled' => ['value' => '1', 'type' => ShopSetting::TYPE_BOOLEAN, 'description' => 'クレジットカード決済有効'],
            'payment.bank_transfer_enabled' => ['value' => '1', 'type' => ShopSetting::TYPE_BOOLEAN, 'description' => '銀行振込有効'],
            'payment.tax_rate' => ['value' => '10', 'type' => ShopSetting::TYPE_INTEGER, 'description' => '消費税率（%）'],
            'payment.tax_included' => ['value' => '1', 'type' => ShopSetting::TYPE_BOOLEAN, 'description' => '税込表示'],
            'payment.bank_transfer_deadline_days' => ['value' => '7', 'type' => ShopSetting::TYPE_INTEGER, 'description' => '銀行振込期限（日）'],
            'payment.bank_name' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '銀行名'],
            'payment.bank_branch' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '支店名'],
            'payment.account_type' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '口座種別'],
            'payment.account_number' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '口座番号'],
            'payment.account_name' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '口座名義'],
            
            // 特定商取引法表記
            'legal.company_name' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '販売業者名'],
            'legal.representative_name' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '代表者名'],
            'legal.company_phone' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '電話番号'],
            'legal.company_address' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '所在地'],
            'legal.additional_charges' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '商品以外の必要料金'],
            'legal.payment_timing' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '代金の支払時期'],
            'legal.delivery_timing' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '商品の引渡時期'],
            'legal.return_policy' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '返品・交換の条件'],
            
            // プライバシーポリシー
            'privacy.privacy_company_name' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '事業者名'],
            'privacy.privacy_updated_date' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '最終更新日'],
            'privacy.collection_purpose' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '収集・利用目的'],
            'privacy.collected_information' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '収集項目'],
            'privacy.third_party_provision' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '第三者提供について'],
            'privacy.information_management' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '管理・保護体制'],
            'privacy.customer_rights' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '開示・訂正・削除等の権利'],
            'privacy.cookie_policy' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => 'Cookie使用について'],
            'privacy.contact_information' => ['value' => '', 'type' => ShopSetting::TYPE_STRING, 'description' => '問い合わせ先情報'],
        ];

        foreach ($defaults as $key => $setting) {
            $parts = explode('.', $key);
            $group = $parts[0];
            
            ShopSetting::setValue(
                $key,
                $setting['value'],
                $setting['type'],
                $group,
                $setting['description']
            );
        }
    }
} 