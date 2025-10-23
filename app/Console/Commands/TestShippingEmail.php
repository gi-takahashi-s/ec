<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Notifications\ShippingNotification;
use Illuminate\Console\Command;

class TestShippingEmail extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:shipping-email {order_id}';

    /**
     * The console command description.
     */
    protected $description = '発送通知メールのテスト送信';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        $order = Order::with(['user', 'items.product', 'shippingAddress'])->find($orderId);
        
        if (!$order) {
            $this->error("注文ID {$orderId} が見つかりません。");
            return 1;
        }

        try {
            $notification = new ShippingNotification($order);
            $mailMessage = $notification->toMail($order->user);
            
            $this->info("発送通知メールのテスト結果:");
            $this->info("件名: " . $mailMessage->subject);
            $this->info("本文:");
            $this->line($mailMessage->introLines[0] ?? '');
            
            // 実際にメール送信をテストする場合
            // $order->user->notify($notification);
            // $this->info("メールを送信しました。");
            
        } catch (\Exception $e) {
            $this->error("エラーが発生しました: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
