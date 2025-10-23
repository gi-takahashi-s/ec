<?php

namespace App\Helpers;

class EmailTemplateHelper
{
    /**
     * 共通のテンプレート変数
     */
    const COMMON_VARIABLES = [
        '{ショップ名}' => 'ショップ名',
        '{ショップメールアドレス}' => 'ショップメールアドレス',
        '{ショップ電話番号}' => 'ショップ電話番号',
        '{ショップ営業時間}' => 'ショップ営業時間',
        '{ショップURL}' => 'ショップURL',
    ];

    /**
     * 注文関連の基本変数
     */
    const ORDER_BASIC_VARIABLES = [
        '{注文番号}' => '注文番号',
        '{注文日時}' => '注文日時',
        '{購入者名}' => '購入者名',
        '{お支払い方法}' => 'お支払い方法',
    ];

    /**
     * 注文内容関連の変数
     */
    const ORDER_CONTENT_VARIABLES = [
        '{注文内容}' => '商品詳細から合計金額まで',
        '{商品小計}' => '商品小計',
        '{送料}' => '送料',
        '{代金引換手数料}' => '代金引換手数料',
        '{合計金額}' => '合計金額',
    ];

    /**
     * 銀行振込関連の変数
     */
    const BANK_TRANSFER_VARIABLES = [
        '{振込先銀行}' => '振込先銀行名（銀行振込時）',
        '{振込先支店}' => '振込先支店名（銀行振込時）',
        '{口座番号}' => '口座番号（銀行振込時）',
        '{口座名義}' => '口座名義（銀行振込時）',
        '{振込み期限}' => '振込み期限（銀行振込時）',
    ];

    /**
     * 配送関連の変数
     */
    const SHIPPING_VARIABLES = [
        '{発送日}' => '発送日',
        '{配送業者}' => '配送業者名',
        '{お届け予定日}' => 'お届け予定日',
        '{お届け時間}' => 'お届け時間',
        '{追跡番号}' => '追跡番号（お問い合わせ番号）',
    ];

    /**
     * 会員関連の変数
     */
    const MEMBER_VARIABLES = [
        '{お客様名}' => 'お客様名',
        '{verification_url}' => '認証URL（会員仮登録時）',
    ];

    /**
     * 支払い方法関連の変数
     */
    const PAYMENT_VARIABLES = [
        '{代金引換手数料の注意事項設定}' => '代金引換手数料の注意事項',
        '{代金引換の注意事項設定}' => '代金引換の注意事項（代金引換時）',
    ];

    /**
     * メールタイプ別の利用可能な変数を取得
     */
    public static function getVariablesByType($type)
    {
        switch ($type) {
            case 'order_received':
                return array_merge(
                    self::COMMON_VARIABLES,
                    self::ORDER_BASIC_VARIABLES,
                    self::ORDER_CONTENT_VARIABLES,
                    self::BANK_TRANSFER_VARIABLES,
                    self::PAYMENT_VARIABLES
                );

            case 'shipping_notification':
                return array_merge(
                    self::COMMON_VARIABLES,
                    ['{注文番号}' => '注文番号'],
                    self::MEMBER_VARIABLES,
                    self::SHIPPING_VARIABLES,
                    ['{代金引換の注意事項設定}' => '代金引換の注意事項（代金引換時）']
                );

            case 'member_provisional':
                return array_merge(
                    self::COMMON_VARIABLES,
                    [
                        '{お客様名}' => 'お客様名',
                        '{verification_url}' => '認証URL（会員仮登録時）'
                    ]
                );

            case 'member_registration':
                return array_merge(
                    self::COMMON_VARIABLES,
                    self::MEMBER_VARIABLES
                );

            case 'member_withdrawal':
                return array_merge(
                    self::COMMON_VARIABLES,
                    self::MEMBER_VARIABLES
                );

            default:
                return self::COMMON_VARIABLES;
        }
    }

    /**
     * 条件分岐の使用方法を取得
     */
    public static function getConditionalUsage()
    {
        return [
            'description' => '支払い方法によって内容を変更したい場合は、以下の形式で記述してください：',
            'examples' => [
                '@if(支払い方法 == \'代金引換\')',
                '代金引換の場合の内容',
                '@endif',
                '',
                '@if(支払い方法 == \'クレジットカード\')',
                'クレジットカードの場合の内容',
                '@endif',
                '',
                '@if(支払い方法 == \'銀行振込\')',
                '銀行振込の場合の内容',
                '@endif'
            ]
        ];
    }

    /**
     * メールタイプ別の特別な注意事項を取得
     */
    public static function getSpecialNotes($type)
    {
        switch ($type) {
            case 'member_provisional':
                return '※ {verification_url} は認証URLに自動置換されます';

            case 'shipping_notification':
                return '※ 配送情報（発送日、配送業者、お届け予定日など）が未設定の場合は空白で表示されます';

            default:
                return null;
        }
    }

    /**
     * 全ての利用可能な変数を取得（管理用）
     */
    public static function getAllVariables()
    {
        return array_merge(
            self::COMMON_VARIABLES,
            self::ORDER_BASIC_VARIABLES,
            self::ORDER_CONTENT_VARIABLES,
            self::BANK_TRANSFER_VARIABLES,
            self::SHIPPING_VARIABLES,
            self::MEMBER_VARIABLES,
            self::PAYMENT_VARIABLES
        );
    }
} 