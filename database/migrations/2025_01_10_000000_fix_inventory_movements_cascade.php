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
        Schema::table('inventory_movements', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['goods_receipt_item_id']);
            
            // Re-add with onDelete('cascade')
            $table->foreign('goods_receipt_item_id')
                ->references('id')
                ->on('goods_receipt_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            // Drop the cascading foreign key
            $table->dropForeign(['goods_receipt_item_id']);
            
            // Re-add without cascade (restore original state)
            $table->foreign('goods_receipt_item_id')
                ->references('id')
                ->on('goods_receipt_items');
        });
    }
};
