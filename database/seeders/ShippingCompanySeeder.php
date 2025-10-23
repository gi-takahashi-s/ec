<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingCompany;

class ShippingCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'ヤマト運輸',
                'method_name' => '宅急便',
                'payment_methods' => ['credit_card', 'bank_transfer', 'cash_on_delivery'],
                'delivery_times' => [
                    '午前中（8:00-12:00）',
                    '14:00-16:00',
                    '16:00-18:00',
                    '18:00-20:00',
                    '19:00-21:00'
                ],
                'uniform_shipping_fee' => true,
                'uniform_fee' => 800,
                'prefecture_fees' => null,
                'notes' => '全国一律料金でお届けします。時間指定可能です。',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '佐川急便',
                'method_name' => '飛脚宅配便',
                'payment_methods' => ['credit_card', 'bank_transfer', 'cash_on_delivery'],
                'delivery_times' => [
                    '午前中（9:00-12:00）',
                    '12:00-14:00',
                    '14:00-16:00',
                    '16:00-18:00',
                    '18:00-21:00'
                ],
                'uniform_shipping_fee' => false,
                'uniform_fee' => null,
                'prefecture_fees' => [
                    '北海道' => 1200,
                    '青森県' => 700, '岩手県' => 700, '宮城県' => 700, '秋田県' => 700, '山形県' => 700, '福島県' => 700,
                    '茨城県' => 600, '栃木県' => 600, '群馬県' => 600, '埼玉県' => 600, '千葉県' => 600, '東京都' => 600, '神奈川県' => 600,
                    '新潟県' => 600, '富山県' => 600, '石川県' => 600, '福井県' => 600, '山梨県' => 600, '長野県' => 600,
                    '岐阜県' => 600, '静岡県' => 600, '愛知県' => 600, '三重県' => 600,
                    '滋賀県' => 700, '京都府' => 700, '大阪府' => 700, '兵庫県' => 700, '奈良県' => 700, '和歌山県' => 700,
                    '鳥取県' => 800, '島根県' => 800, '岡山県' => 800, '広島県' => 800, '山口県' => 800,
                    '徳島県' => 900, '香川県' => 900, '愛媛県' => 900, '高知県' => 900,
                    '福岡県' => 1000, '佐賀県' => 1000, '長崎県' => 1000, '熊本県' => 1000, '大分県' => 1000, '宮崎県' => 1000, '鹿児島県' => 1000,
                    '沖縄県' => 1500
                ],
                'notes' => '都道府県別料金設定です。離島は別途料金がかかる場合があります。',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => '日本郵便',
                'method_name' => 'ゆうパック',
                'payment_methods' => ['credit_card', 'bank_transfer'],
                'delivery_times' => [
                    '午前中',
                    '12:00頃',
                    '14:00頃',
                    '16:00頃',
                    '18:00頃',
                    '20:00頃'
                ],
                'uniform_shipping_fee' => true,
                'uniform_fee' => 600,
                'prefecture_fees' => null,
                'notes' => '郵便局の配送サービスです。代金引換は対応しておりません。',
                'is_active' => true,
                'sort_order' => 3,
            ]
        ];

        foreach ($companies as $company) {
            ShippingCompany::create($company);
        }
    }
}
