<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Determine actual foreign key name (if any) for item_id and drop it safely.
        $fk = DB::select("SELECT CONSTRAINT_NAME as name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'goods_receipt_items' AND COLUMN_NAME = 'item_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
        if (!empty($fk) && !empty($fk[0]->name)) {
            $fkName = $fk[0]->name;
            Schema::table('goods_receipt_items', function (Blueprint $table) use ($fkName) {
                // drop by explicit constraint name
                $table->dropForeign($fkName);
            });
        } else {
            // Fallback: attempt to drop by column-based convention inside try/catch
            try {
                Schema::table('goods_receipt_items', function (Blueprint $table) {
                    $table->dropForeign(['item_id']);
                });
            } catch (\Exception $e) {
                // ignore if foreign key doesn't exist
            }
        }

        // Modify the column to be nullable and re-add FK with onDelete set null
        Schema::table('goods_receipt_items', function (Blueprint $table) {
            $table->foreignId('item_id')->nullable()->change();
            $table->foreign('item_id')
                  ->references('id')
                  ->on('items')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('goods_receipt_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->foreignId('item_id')->change();
            $table->foreign('item_id')
                  ->references('id')
                  ->on('items');
        });
    }
};