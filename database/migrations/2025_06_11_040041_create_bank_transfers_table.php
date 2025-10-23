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
        Schema::create('bank_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // 振込先銀行情報
            $table->string('bank_name'); // 銀行名
            $table->string('branch_name'); // 支店名
            $table->string('account_type'); // 口座種別（普通・当座）
            $table->string('account_number'); // 口座番号
            $table->string('account_holder'); // 口座名義
            
            // 振込情報
            $table->integer('transfer_amount'); // 振込金額
            $table->datetime('transfer_deadline'); // 振込期限
            $table->datetime('transfer_confirmed_at')->nullable(); // 振込確認日時
            $table->string('transfer_status')->default('pending'); // 振込ステータス（pending, confirmed, expired）
            
            // 管理用
            $table->text('admin_notes')->nullable(); // 管理者メモ
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete(); // 確認者
            
            $table->timestamps();
            
            // インデックス
            $table->index(['transfer_status', 'transfer_deadline']);
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
        Schema::dropIfExists('bank_transfers');
    }
};
