<?php

namespace App\Notifications;

use App\Models\EmailSetting;
use App\Models\Order;
use App\Models\ShopSetting;
use App\Models\PaymentMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShippingNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $emailSetting = EmailSetting::getByType(EmailSetting::TYPE_SHIPPING_NOTIFICATION);
        
        if (!$emailSetting) {
            // デフォルト設定を作成
            EmailSetting::createDefaults();
            $emailSetting = EmailSetting::getByType(EmailSetting::TYPE_SHIPPING_NOTIFICATION);
        }

        $subject = $this->replaceVariables($emailSetting->subject);
        $body = $this->replaceVariables($emailSetting->body);

        // 改行文字を<br>タグに変換
        $formattedBody = str_replace("\n", "<br>", $body);
        
        return (new MailMessage)
            ->subject($subject)
            ->markdown('mail.custom-message', ['body' => $formattedBody]);
    }

    /**
     * テンプレート変数を実際の値に置換
     */
    private function replaceVariables($template)
    {
        // ショップ設定を取得
        $shopSettings = ShopSetting::getSettings();
        
        // 基本変数の置換
        $variables = [
            '{ショップ名}' => $shopSettings['shop_name'] ?? 'ECサイト',
            '{注文番号}' => $this->order->order_number,
            '{お客様名}' => $this->order->user->name ?? '',
            '{発送日}' => $this->order->shipped_at ? $this->order->shipped_at->format('Y年m月d日') : '',
            '{配送業者}' => $this->order->shipping_method ?? '',
            '{お届け予定日}' => $this->order->delivery_date ? $this->order->delivery_date->format('Y年m月d日') : '',
            '{お届け時間}' => $this->order->delivery_time ?? '',
            '{追跡番号}' => $this->order->tracking_number ?? '',
            '{ショップメールアドレス}' => $shopSettings['contact_email'] ?? 'info@example.com',
            '{ショップ電話番号}' => $shopSettings['contact_phone'] ?? '03-0000-0000',
            '{ショップ営業時間}' => $shopSettings['business_hours'] ?? '平日 9:00-18:00',
            '{ショップURL}' => $shopSettings['shop_url'] ?? url('/'),
        ];

        // 代金引換の注意事項
        if ($this->order->payment_method === 'cash_on_delivery') {
            $paymentMethod = PaymentMethod::getByKey('cash_on_delivery');
            $variables['{代金引換の注意事項設定}'] = $paymentMethod->settings['notes'] ?? '';
        }

        // 基本変数の置換
        $result = str_replace(array_keys($variables), array_values($variables), $template);

        // if文パターンの条件分岐処理
        $result = $this->processIfStatements($result);

        return $result;
    }

    /**
     * if文パターンの条件分岐を処理
     */
    private function processIfStatements($template)
    {
        $paymentMethodName = $this->getPaymentMethodName($this->order->payment_method);
        
        // @if文の処理
        $pattern = '/@if\s*\(\s*支払い方法\s*==\s*[\'"]([^\'"]+)[\'"]\s*\)(.*?)@endif/s';
        
        $result = preg_replace_callback($pattern, function($matches) use ($paymentMethodName) {
            $conditionPaymentMethod = $matches[1];
            $content = $matches[2];
            
            // 条件に一致する場合のみ内容を返す
            if ($paymentMethodName === $conditionPaymentMethod) {
                return trim($content);
            }
            
            return '';
        }, $template);

        return $result;
    }

    /**
     * 支払い方法名を取得
     */
    private function getPaymentMethodName($paymentMethod)
    {
        $names = [
            'cash_on_delivery' => '代金引換',
            'credit_card' => 'クレジットカード',
            'stripe' => 'クレジットカード',
            'bank_transfer' => '銀行振込',
        ];

        return $names[$paymentMethod] ?? $paymentMethod;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
