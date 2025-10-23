<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailJapanese extends VerifyEmail
{
    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('メールアドレスの認証')
            ->greeting('こんにちは！')
            ->line('ご登録ありがとうございます。')
            ->line('下記のボタンをクリックして、メールアドレスの認証を完了してください。')
            ->action('メールアドレスを認証する', $url)
            ->line('このメールに心当たりがない場合は、何もする必要はありません。')
            ->salutation('よろしくお願いいたします。');
    }
} 