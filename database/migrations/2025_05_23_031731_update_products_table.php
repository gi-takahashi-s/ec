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
        Schema::table('products', function (Blueprint $table) {
            // 既存フィールドの確認
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->unique()->after('name'); // URLスラグ
            }
            if (!Schema::hasColumn('products', 'features')) {
                $table->text('features')->nullable()->after('description'); // 商品特徴
            }
            if (!Schema::hasColumn('products', 'specifications')) {
                $table->text('specifications')->nullable()->after('features'); // 商品仕様
            }
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('price'); // セール価格
            }
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->unique()->after('stock'); // 商品コード
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active'); // おすすめ商品かどうか
            }
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('image'); // カテゴリーID
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('products', 'features')) {
                $table->dropColumn('features');
            }
            if (Schema::hasColumn('products', 'specifications')) {
                $table->dropColumn('specifications');
            }
            if (Schema::hasColumn('products', 'sale_price')) {
                $table->dropColumn('sale_price');
            }
            if (Schema::hasColumn('products', 'sku')) {
                $table->dropColumn('sku');
            }
            if (Schema::hasColumn('products', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
            if (Schema::hasColumn('products', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });
    }
};
