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
            $table->foreignId('shipping_company_id')->nullable()->constrained()->nullOnDelete()->comment('配送業者ID');
            $table->string('selected_delivery_time')->nullable()->comment('選択された配送時間');
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
            $table->dropForeign(['shipping_company_id']);
            $table->dropColumn(['shipping_company_id', 'selected_delivery_time']);
        });
    }
};
