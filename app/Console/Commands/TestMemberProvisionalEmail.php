<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\MemberProvisionalNotification;
use Illuminate\Console\Command;

class TestMemberProvisionalEmail extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:member-provisional-email {user_id}';

    /**
     * The console command description.
     */
    protected $description = '会員仮登録メールのテスト送信';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("ユーザーID {$userId} が見つかりません。");
            return 1;
        }

        try {
            // テスト用の認証URLを生成
            $verificationUrl = url('/verify-email/' . base64_encode($user->email) . '?signature=test_signature');
            
            $notification = new MemberProvisionalNotification($user, $verificationUrl);
            $mailMessage = $notification->toMail($user);
            
            $this->info("会員仮登録メールのテスト結果:");
            $this->info("件名: " . $mailMessage->subject);
            $this->info("本文:");
            
            // markdownやviewを使用している場合の対応
            if (!empty($mailMessage->introLines)) {
                $this->line($mailMessage->introLines[0] ?? '');
            } else {
                $this->line("カスタムビューまたはMarkdownテンプレートを使用しています。");
                if (isset($mailMessage->markdown)) {
                    $this->line("Markdownテンプレート: " . $mailMessage->markdown);
                }
                if (isset($mailMessage->view)) {
                    $this->line("ビューテンプレート: " . $mailMessage->view);
                }
                
                // 実際にメールを送信してログファイルで確認
                $user->notify($notification);
                $this->info("テストメールを送信しました。ログファイルでメール内容を確認してください。");
            }
            
        } catch (\Exception $e) {
            $this->error("エラーが発生しました: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 