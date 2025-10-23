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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique()->comment('メールタイプ（order_received, shipping_notification, member_provisional, member_registration, member_withdrawal）');
            $table->string('subject')->comment('メール件名');
            $table->text('body')->comment('メール本文');
            $table->boolean('is_active')->default(true)->comment('有効フラグ');
            $table->timestamps();
            
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
