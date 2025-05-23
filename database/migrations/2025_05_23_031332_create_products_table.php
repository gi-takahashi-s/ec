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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 商品名
            $table->string('slug')->unique(); // URLスラグ
            $table->text('description')->nullable(); // 商品説明
            $table->text('features')->nullable(); // 商品特徴
            $table->text('specifications')->nullable(); // 商品仕様
            $table->decimal('price', 10, 2); // 価格
            $table->decimal('sale_price', 10, 2)->nullable(); // セール価格
            $table->integer('stock')->default(0); // 在庫数
            $table->string('sku')->unique(); // 商品コード
            $table->boolean('is_visible')->default(true); // 表示/非表示
            $table->boolean('is_featured')->default(false); // おすすめ商品かどうか
            $table->string('image_path')->nullable(); // メイン画像パス
            $table->unsignedBigInteger('category_id')->nullable(); // カテゴリーID
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
