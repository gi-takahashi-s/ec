<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderReceivedNotification;
use Illuminate\Console\Command;

class TestOrderEmail extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:order-email {order_id}';

    /**
     * The console command description.
     */
    protected $description = '注文受付メールのテスト送信';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        $order = Order::with(['user', 'items.product', 'shippingAddress', 'bankTransfer'])->find($orderId);
        
        if (!$order) {
            $this->error("注文ID {$orderId} が見つかりません。");
            return 1;
        }

        try {
            $notification = new OrderReceivedNotification($order);
            $mailMessage = $notification->toMail($order->user);
            
            $this->info("注文受付メールのテスト結果:");
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