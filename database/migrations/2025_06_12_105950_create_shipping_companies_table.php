<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 配送業者名
            $table->string('method_name'); // 配送方法名称
            $table->json('payment_methods')->nullable(); // 取り扱う支払方法
            $table->json('delivery_times')->nullable(); // お届け時間設定
            $table->boolean('uniform_shipping_fee')->default(false); // 全国一律送料設定
            $table->integer('uniform_fee')->nullable(); // 全国一律送料金額
            $table->json('prefecture_fees')->nullable(); // 都道府県別送料
            $table->text('notes')->nullable(); // 配送に関する備考
            $table->boolean('is_active')->default(true); // 有効/無効
            $table->integer('sort_order')->default(0); // 表示順序
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_companies');
    }
};
