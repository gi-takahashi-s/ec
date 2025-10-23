<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\MemberWithdrawalNotification;
use Illuminate\Console\Command;

class TestMemberWithdrawalEmail extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:member-withdrawal-email {user_id}';

    /**
     * The console command description.
     */
    protected $description = '会員退会メールのテスト送信';

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
            $notification = new MemberWithdrawalNotification($user);
            $mailMessage = $notification->toMail($user);
            
            $this->info("会員退会メールのテスト結果:");
            $this->info("件名: " . $mailMessage->subject);
            $this->info("本文:");
            $this->line($mailMessage->introLines[0] ?? '');
            
            // 実際にメール送信をテストする場合
            // $user->notify($notification);
            // $this->info("メールを送信しました。");
            
        } catch (\Exception $e) {
            $this->error("エラーが発生しました: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 