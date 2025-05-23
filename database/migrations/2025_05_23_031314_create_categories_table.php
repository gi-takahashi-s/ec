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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // カテゴリー名
            $table->string('slug')->unique(); // URLスラグ
            $table->text('description')->nullable(); // 説明
            $table->integer('sort_order')->default(0); // 表示順
            $table->boolean('is_visible')->default(true); // 表示/非表示
            $table->string('image_path')->nullable(); // カテゴリー画像のパス
            $table->unsignedBigInteger('parent_id')->nullable(); // 親カテゴリーID
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('set null');
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
        Schema::dropIfExists('categories');
    }
};
