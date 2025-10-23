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
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('設定キー');
            $table->string('group')->index()->comment('設定グループ（basic_info, shipping, legal, payment）');
            $table->text('value')->nullable()->comment('設定値');
            $table->string('type')->default('string')->comment('データ型（string, integer, boolean, json）');
            $table->text('description')->nullable()->comment('設定項目の説明');
            $table->boolean('is_active')->default(true)->comment('設定の有効/無効');
            $table->integer('sort_order')->default(0)->comment('表示順序');
            $table->timestamps();
            
            // インデックス
            $table->index(['group', 'is_active']);
            $table->index(['key', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_settings');
    }
};
