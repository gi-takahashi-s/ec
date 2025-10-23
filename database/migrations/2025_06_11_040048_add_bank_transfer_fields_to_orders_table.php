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
            // 振込期限フィールドを追加
            $table->datetime('transfer_deadline')->nullable()->after('paid_at');
            // 振込確認日時フィールドを追加
            $table->datetime('transfer_confirmed_at')->nullable()->after('transfer_deadline');
            
            // インデックスを追加
            $table->index('transfer_deadline');
            $table->index('transfer_confirmed_at');
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
            $table->dropIndex(['transfer_deadline']);
            $table->dropIndex(['transfer_confirmed_at']);
            $table->dropColumn(['transfer_deadline', 'transfer_confirmed_at']);
        });
    }
};
