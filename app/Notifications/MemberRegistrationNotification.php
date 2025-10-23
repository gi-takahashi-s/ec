<?php

namespace App\Notifications;

use App\Models\EmailSetting;
use App\Models\ShopSetting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberRegistrationNotification extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        $emailSetting = EmailSetting::getByType(EmailSetting::TYPE_MEMBER_REGISTRATION);
        
        if (!$emailSetting) {
            // デフォルト設定を作成
            EmailSetting::createDefaults();
            $emailSetting = EmailSetting::getByType(EmailSetting::TYPE_MEMBER_REGISTRATION);
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
            '{お客様名}' => $this->user->name ?? '',
            '{ショップメールアドレス}' => $shopSettings['contact_email'] ?? 'info@example.com',
            '{ショップ電話番号}' => $shopSettings['contact_phone'] ?? '03-0000-0000',
            '{ショップ営業時間}' => $shopSettings['business_hours'] ?? '平日 9:00-18:00',
            '{ショップURL}' => $shopSettings['shop_url'] ?? url('/'),
        ];

        // 基本変数の置換
        $result = str_replace(array_keys($variables), array_values($variables), $template);

        return $result;
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