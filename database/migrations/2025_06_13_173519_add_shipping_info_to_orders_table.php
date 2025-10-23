<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // 配送情報フィールドを追加
            $table->string('tracking_number')->nullable()->comment('お問い合わせ番号（配送業者のお問い合わせ番号）');
            $table->string('shipping_method')->nullable()->comment('配送方法（配送設定の設定値）');
            $table->date('delivery_date')->nullable()->comment('お届け日');
            $table->string('delivery_time')->nullable()->comment('お届け時間');
            $table->text('shipping_memo')->nullable()->comment('出荷用メモ欄');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_number',
                'shipping_method',
                'delivery_date',
                'delivery_time',
                'shipping_memo'
            ]);
        });
    }
};
