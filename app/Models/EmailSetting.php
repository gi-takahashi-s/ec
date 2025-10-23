<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'subject',
        'body',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // メールタイプの定数
    const TYPE_ORDER_RECEIVED = 'order_received';
    const TYPE_SHIPPING_NOTIFICATION = 'shipping_notification';
    const TYPE_MEMBER_PROVISIONAL = 'member_provisional';
    const TYPE_MEMBER_REGISTRATION = 'member_registration';
    const TYPE_MEMBER_WITHDRAWAL = 'member_withdrawal';

    // メールタイプの日本語名
    const TYPE_NAMES = [
        self::TYPE_ORDER_RECEIVED => '注文受付メール',
        self::TYPE_SHIPPING_NOTIFICATION => '発送通知メール',
        self::TYPE_MEMBER_PROVISIONAL => '会員仮登録メール',
        self::TYPE_MEMBER_REGISTRATION => '会員本登録メール',
        self::TYPE_MEMBER_WITHDRAWAL => '会員退会メール',
    ];

    /**
     * メールタイプの日本語名を取得
     */
    public function getTypeNameAttribute()
    {
        return self::TYPE_NAMES[$this->type] ?? $this->type;
    }

    /**
     * 指定されたタイプのメール設定を取得
     */
    public static function getByType($type)
    {
        return self::where('type', $type)->where('is_active', true)->first();
    }

    /**
     * 全てのメールタイプを取得
     */
    public static function getAllTypes()
    {
        return self::TYPE_NAMES;
    }

    /**
     * デフォルトのメール設定を作成
     */
    public static function createDefaults()
    {
        $defaults = [
            self::TYPE_ORDER_RECEIVED => [
                'subject' => '【{ショップ名}】ご注文を承りました（注文番号：{注文番号}）',
                'body' => '{購入者名} 様

この度は、{ショップ名}をご利用いただき、誠にありがとうございます。
ご注文を承りましたので、詳細をご確認ください。

■ご注文情報
注文番号：{注文番号}
注文日時：{注文日時}
お支払い方法：{お支払い方法}

■{注文内容}

@if(支払い方法 == \'代金引換\')
商品は、ご注文確認後、通常2-3営業日以内に発送いたします。
商品到着時に配送業者へ代金をお支払いください。

代金引換手数料について：
{代金引換手数料の注意事項設定}
@endif

@if(支払い方法 == \'クレジットカード\')
クレジットカードでのお支払いが完了いたしました。
商品は、ご注文確認後、通常2-3営業日以内に発送いたします。
@endif

@if(支払い方法 == \'銀行振込\')
お振込み先は以下の通りです。
お振込み確認後、商品を発送いたします。

【お振込み先】
銀行名：{振込先銀行}
支店名：{振込先支店}
口座番号：{口座番号}
口座名義：{口座名義}
お振込み期限：{振込み期限}

※お振込み手数料はお客様のご負担となります。
※期限内にお振込みが確認できない場合、ご注文をキャンセルさせていただく場合がございます。
@endif

■お問い合わせ先
{ショップ名}
メールアドレス：{ショップメールアドレス}
電話番号：{ショップ電話番号}
営業時間：{ショップ営業時間}
ショップURL：{ショップURL}

今後ともよろしくお願いいたします。',
                'is_active' => true,
            ],
            self::TYPE_SHIPPING_NOTIFICATION => [
                'subject' => '【{ショップ名}】商品を発送いたしました（注文番号：{注文番号}）',
                'body' => '{お客様名}様
いつも{ショップ名}をご利用いただき、誠にありがとうございます。
ご注文いただきました商品の発送が完了いたしましたので、ご連絡いたします。

発送情報
注文番号： {注文番号}
発送日： {発送日}
配送業者： {配送業者}
お届け予定日： {お届け予定日}
お届け時間： {お届け時間}

下記URLまたは追跡番号にて、配送状況をご確認いただけます。
ヤマト運輸 荷物お問い合わせシステム

https://toi.kuronekoyamato.co.jp/cgi-bin/tneko
お問い合わせ番号： {追跡番号}
※配送状況の反映には、発送から数時間お時間をいただく場合がございます。

@if(支払い方法 == \'代金引換\')
【代金引換決済のお客様】
商品お届けの際に合計金額を配送員にお支払いください。

代金引換の注意事項
{代金引換の注意事項設定}
@endif

■お問い合わせ先
{ショップ名}
メールアドレス：{ショップメールアドレス}
電話番号：{ショップ電話番号}
営業時間：{ショップ営業時間}
ショップURL：{ショップURL}

今後ともよろしくお願いいたします。',
                'is_active' => true,
            ],
            self::TYPE_MEMBER_PROVISIONAL => [
                'subject' => '【{ショップ名}】会員仮登録が完了しました',
                'body' => '{お客様名} 様

この度は、{ショップ名}にご登録いただき、誠にありがとうございます。
会員仮登録が完了いたしました。

下記のURLをクリックして、本登録を完了してください。

■本登録URL
{verification_url}

※このメールは24時間以内に認証を完了してください。
※認証URLの有効期限が切れた場合は、お手数ですが再度会員登録を行ってください。

本登録が完了いたしますと、会員限定の特典やお得な情報をお届けいたします。
ぜひお買い物をお楽しみください。

■お問い合わせ先
{ショップ名}
メールアドレス：{ショップメールアドレス}
電話番号：{ショップ電話番号}
営業時間：{ショップ営業時間}
ショップURL：{ショップURL}

今後ともよろしくお願いいたします。',
                'is_active' => true,
            ],
            self::TYPE_MEMBER_REGISTRATION => [
                'subject' => '【{ショップ名}】会員登録が完了しました',
                'body' => '{お客様名} 様

この度は、{ショップ名}にご登録いただき、誠にありがとうございます。
会員登録が正常に完了いたしました。

今後は会員限定の特典やお得な情報をお届けいたします。
ぜひお買い物をお楽しみください。

■お問い合わせ先
{ショップ名}
メールアドレス：{ショップメールアドレス}
電話番号：{ショップ電話番号}
営業時間：{ショップ営業時間}
ショップURL：{ショップURL}

今後ともよろしくお願いいたします。',
                'is_active' => true,
            ],
            self::TYPE_MEMBER_WITHDRAWAL => [
                'subject' => '【{ショップ名}】退会手続きが完了しました',
                'body' => '{お客様名} 様

この度は、{ショップ名}の退会手続きをいただき、ありがとうございます。
退会手続きが正常に完了いたしました。

長い間ご利用いただき、誠にありがとうございました。
お客様にご満足いただけるサービスを提供できず、申し訳ございませんでした。

今後サービスの改善に努めてまいりますので、
機会がございましたら、またのご利用をお待ちしております。

■お問い合わせ先
{ショップ名}
メールアドレス：{ショップメールアドレス}
電話番号：{ショップ電話番号}
営業時間：{ショップ営業時間}
ショップURL：{ショップURL}

ありがとうございました。',
                'is_active' => true,
            ],
        ];

        foreach ($defaults as $type => $data) {
            self::updateOrCreate(
                ['type' => $type],
                [
                    'subject' => $data['subject'],
                    'body' => $data['body'],
                    'is_active' => true,
                ]
            );
        }
    }
}
