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
        // 既存データを一時的に保存
        $products = DB::table('products')->select('id', 'price', 'sale_price')->get();
        
        Schema::table('products', function (Blueprint $table) {
            // 既存のカラムを削除
            $table->dropColumn(['price', 'sale_price']);
        });
        
        Schema::table('products', function (Blueprint $table) {
            // 新しいintegerカラムを追加
            $table->integer('price')->after('specifications');
            $table->integer('sale_price')->nullable()->after('price');
        });
        
        // データを復元（小数点以下は切り捨て）
        foreach ($products as $product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'price' => (int) $product->price,
                    'sale_price' => $product->sale_price ? (int) $product->sale_price : null,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 既存データを一時的に保存
        $products = DB::table('products')->select('id', 'price', 'sale_price')->get();
        
        Schema::table('products', function (Blueprint $table) {
            // 既存のカラムを削除
            $table->dropColumn(['price', 'sale_price']);
        });
        
        Schema::table('products', function (Blueprint $table) {
            // 元のdecimalカラムを追加
            $table->decimal('price', 10, 2)->after('specifications');
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');
        });
        
        // データを復元
        foreach ($products as $product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'price' => $product->price,
                    'sale_price' => $product->sale_price,
                ]);
        }
    }
};
