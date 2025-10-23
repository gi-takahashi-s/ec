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

class OrderReceivedNotification extends Notification
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
        $emailSetting = EmailSetting::getByType(EmailSetting::TYPE_ORDER_RECEIVED);
        
        if (!$emailSetting) {
            // デフォルト設定を作成
            EmailSetting::createDefaults();
            $emailSetting = EmailSetting::getByType(EmailSetting::TYPE_ORDER_RECEIVED);
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
        
        // 代引手数料を計算
        $codFee = 0;
        $codFeeDisplay = '';
        if ($this->order->payment_method === 'cash_on_delivery') {
            $paymentMethod = PaymentMethod::getByKey('cash_on_delivery');
            if ($paymentMethod) {
                $codFee = $paymentMethod->calculateCodFee($this->order->subtotal);
                $codFeeDisplay = number_format($codFee) . '円';
            }
        }

        // 注文内容の生成
        $orderContent = $this->generateOrderContent($codFeeDisplay);

        // 基本変数の置換
        $variables = [
            '{ショップ名}' => $shopSettings['shop_name'] ?? 'ECサイト',
            '{注文番号}' => $this->order->order_number,
            '{注文日時}' => $this->order->created_at->format('Y年m月d日 H:i'),
            '{購入者名}' => $this->order->user->name,
            '{お支払い方法}' => $this->getPaymentMethodName($this->order->payment_method),
            '{注文内容}' => $orderContent,
            '{商品小計}' => number_format($this->order->subtotal),
            '{送料}' => number_format($this->order->shipping_fee),
            '{代金引換手数料}' => $codFeeDisplay,
            '{合計金額}' => number_format($this->order->total),
            '{ショップメールアドレス}' => $shopSettings['contact_email'] ?? 'info@example.com',
            '{ショップ電話番号}' => $shopSettings['contact_phone'] ?? '03-0000-0000',
            '{ショップ営業時間}' => $shopSettings['business_hours'] ?? '平日 9:00-18:00',
            '{ショップURL}' => $shopSettings['shop_url'] ?? url('/'),
        ];

        // 銀行振込関連の変数
        if ($this->order->payment_method === 'bank_transfer') {
            $bankTransfer = $this->order->bankTransfer;
            if ($bankTransfer) {
                $variables['{振込先銀行}'] = $bankTransfer->bank_name;
                $variables['{振込先支店}'] = $bankTransfer->branch_name;
                $variables['{口座番号}'] = $bankTransfer->account_number;
                $variables['{口座名義}'] = $bankTransfer->account_holder;
                $variables['{振込み期限}'] = $bankTransfer->transfer_deadline->format('Y年m月d日');
                $variables['{注文日から、振込み期限を足した日付}'] = $bankTransfer->transfer_deadline->format('Y年m月d日');
            }
        }

        // 代引手数料の注意事項
        if ($this->order->payment_method === 'cash_on_delivery') {
            $paymentMethod = PaymentMethod::getByKey('cash_on_delivery');
            $variables['{代金引換手数料の注意事項設定}'] = $paymentMethod->settings['notes'] ?? '';
        }

        // 基本変数の置換
        $result = str_replace(array_keys($variables), array_values($variables), $template);

        // if文パターンの条件分岐処理
        $result = $this->processIfStatements($result);

        return $result;
    }

    /**
     * 注文内容を生成
     */
    private function generateOrderContent($codFeeDisplay)
    {
        $content = "商品詳細\n\n";
        
        foreach ($this->order->items as $item) {
            $content .= "商品名：{$item->product_name}\n";
            $content .= "価格：" . number_format($item->price) . "円（税込）\n";
            $content .= "数量：{$item->quantity}個\n\n";
        }
        
        $content .= "商品小計： " . number_format($this->order->subtotal) . "円（税込）\n";
        $content .= "送料： " . number_format($this->order->shipping_fee) . "円（税込）\n";
        
        // 代金引換手数料（該当する場合のみ）
        if ($this->order->payment_method === 'cash_on_delivery' && !empty($codFeeDisplay)) {
            $content .= "代金引換手数料： {$codFeeDisplay}（税込）\n";
        }
        
        $content .= "合計金額： " . number_format($this->order->total) . "円（税込）";
        
        return $content;
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
     * 条件分岐ブロックを処理（旧HTMLコメント形式との互換性のため残す）
     */
    private function processConditionalBlocks($template)
    {
        // 代金引換の場合の条件分岐
        if ($this->order->payment_method === 'cash_on_delivery') {
            $template = preg_replace('/<!-- お支払い方法が代金引換の場合 -->(.*?)<!-- お支払い方法がクレジットカードの場合 -->/s', '$1', $template);
            $template = preg_replace('/<!-- お支払い方法がクレジットカードの場合 -->(.*?)<!-- お支払い方法が銀行振込の場合 -->/s', '', $template);
            $template = preg_replace('/<!-- お支払い方法が銀行振込の場合 -->(.*?)(?=<!--|\z)/s', '', $template);
        }
        // クレジットカードの場合の条件分岐
        elseif ($this->order->payment_method === 'credit_card' || $this->order->payment_method === 'stripe') {
            $template = preg_replace('/<!-- お支払い方法が代金引換の場合 -->(.*?)<!-- お支払い方法がクレジットカードの場合 -->/s', '', $template);
            $template = preg_replace('/<!-- お支払い方法がクレジットカードの場合 -->(.*?)<!-- お支払い方法が銀行振込の場合 -->/s', '$1', $template);
            $template = preg_replace('/<!-- お支払い方法が銀行振込の場合 -->(.*?)(?=<!--|\z)/s', '', $template);
        }
        // 銀行振込の場合の条件分岐
        elseif ($this->order->payment_method === 'bank_transfer') {
            $template = preg_replace('/<!-- お支払い方法が代金引換の場合 -->(.*?)<!-- お支払い方法がクレジットカードの場合 -->/s', '', $template);
            $template = preg_replace('/<!-- お支払い方法がクレジットカードの場合 -->(.*?)<!-- お支払い方法が銀行振込の場合 -->/s', '', $template);
            $template = preg_replace('/<!-- お支払い方法が銀行振込の場合 -->(.*?)(?=<!--|\z)/s', '$1', $template);
        }

        // 残ったHTMLコメントを削除
        $template = preg_replace('/<!--.*?-->/s', '', $template);

        return $template;
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
}