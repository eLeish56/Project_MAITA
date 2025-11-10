<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('goods_receipt_items', function (Blueprint $table) {
            $table->string('batch_number')->nullable()->after('expiry_date');
            $table->string('expiry_status')->nullable()->after('batch_number'); // 'safe', 'warning', 'expired'
            $table->integer('remaining_quantity')->after('quantity_received')->default(0);
        });
    }

    public function down()
    {
        Schema::table('goods_receipt_items', function (Blueprint $table) {
            $table->dropColumn(['batch_number', 'expiry_status', 'remaining_quantity']);
        });
    }
};