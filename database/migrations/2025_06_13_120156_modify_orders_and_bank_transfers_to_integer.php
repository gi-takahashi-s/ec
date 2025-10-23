<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 注文テーブルの修正
        if (Schema::hasTable('orders')) {
            $orders = DB::table('orders')->select('id', 'subtotal', 'tax', 'shipping_fee', 'total')->get();
            
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['subtotal', 'tax', 'shipping_fee', 'total']);
            });
            
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('subtotal')->after('order_number');
                $table->integer('tax')->after('subtotal');
                $table->integer('shipping_fee')->default(0)->after('tax');
                $table->integer('total')->after('shipping_fee');
            });
            
            foreach ($orders as $order) {
                DB::table('orders')
                    ->where('id', $order->id)
                    ->update([
                        'subtotal' => (int) $order->subtotal,
                        'tax' => (int) $order->tax,
                        'shipping_fee' => (int) $order->shipping_fee,
                        'total' => (int) $order->total,
                    ]);
            }
        }
        
        // 注文アイテムテーブルの修正
        if (Schema::hasTable('order_items')) {
            $orderItems = DB::table('order_items')->select('id', 'price', 'subtotal')->get();
            
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn(['price', 'subtotal']);
            });
            
            Schema::table('order_items', function (Blueprint $table) {
                $table->integer('price')->after('quantity');
                $table->integer('subtotal')->after('price');
            });
            
            foreach ($orderItems as $item) {
                DB::table('order_items')
                    ->where('id', $item->id)
                    ->update([
                        'price' => (int) $item->price,
                        'subtotal' => (int) $item->subtotal,
                    ]);
            }
        }
        
        // 銀行振込テーブルの修正
        if (Schema::hasTable('bank_transfers')) {
            $bankTransfers = DB::table('bank_transfers')->select('id', 'transfer_amount')->get();
            
            Schema::table('bank_transfers', function (Blueprint $table) {
                $table->dropColumn('transfer_amount');
            });
            
            Schema::table('bank_transfers', function (Blueprint $table) {
                $table->integer('transfer_amount')->after('order_id');
            });
            
            foreach ($bankTransfers as $transfer) {
                DB::table('bank_transfers')
                    ->where('id', $transfer->id)
                    ->update([
                        'transfer_amount' => (int) $transfer->transfer_amount,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 注文テーブルの復元
        if (Schema::hasTable('orders')) {
            $orders = DB::table('orders')->select('id', 'subtotal', 'tax', 'shipping_fee', 'total')->get();
            
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['subtotal', 'tax', 'shipping_fee', 'total']);
            });
            
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('subtotal', 10, 2)->after('order_number');
                $table->decimal('tax', 10, 2)->after('subtotal');
                $table->decimal('shipping_fee', 10, 2)->default(0)->after('tax');
                $table->decimal('total', 10, 2)->after('shipping_fee');
            });
            
            foreach ($orders as $order) {
                DB::table('orders')
                    ->where('id', $order->id)
                    ->update([
                        'subtotal' => $order->subtotal,
                        'tax' => $order->tax,
                        'shipping_fee' => $order->shipping_fee,
                        'total' => $order->total,
                    ]);
            }
        }
        
        // 注文アイテムテーブルの復元
        if (Schema::hasTable('order_items')) {
            $orderItems = DB::table('order_items')->select('id', 'price', 'subtotal')->get();
            
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn(['price', 'subtotal']);
            });
            
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->after('quantity');
                $table->decimal('subtotal', 10, 2)->after('price');
            });
            
            foreach ($orderItems as $item) {
                DB::table('order_items')
                    ->where('id', $item->id)
                    ->update([
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ]);
            }
        }
        
        // 銀行振込テーブルの復元
        if (Schema::hasTable('bank_transfers')) {
            $bankTransfers = DB::table('bank_transfers')->select('id', 'transfer_amount')->get();
            
            Schema::table('bank_transfers', function (Blueprint $table) {
                $table->dropColumn('transfer_amount');
            });
            
            Schema::table('bank_transfers', function (Blueprint $table) {
                $table->decimal('transfer_amount', 10, 2)->after('order_id');
            });
            
            foreach ($bankTransfers as $transfer) {
                DB::table('bank_transfers')
                    ->where('id', $transfer->id)
                    ->update([
                        'transfer_amount' => $transfer->transfer_amount,
                    ]);
            }
        }
    }
};
