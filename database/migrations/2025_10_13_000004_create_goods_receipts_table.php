<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create the goods_receipts table.
 *
 * Goods receipts (GRNs) document the receipt of goods or services against
 * a purchase order. Each receipt can be matched against PO items for
 * quantity verification and quality control.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->string('gr_number')->unique();
            $table->date('receipt_date');
            $table->foreignId('received_by')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('completed');
            $table->text('notes')->nullable();
            $table->string('receipt_document')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};