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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('決済方法キー（stripe, bank_transfer, cash_on_delivery）');
            $table->string('name')->comment('決済方法名');
            $table->text('description')->nullable()->comment('説明');
            $table->boolean('is_enabled')->default(false)->comment('有効フラグ');
            $table->integer('sort_order')->default(0)->comment('表示順序');
            $table->json('settings')->nullable()->comment('決済方法固有の設定（JSON）');
            $table->timestamps();
            
            $table->index('key');
            $table->index('is_enabled');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
