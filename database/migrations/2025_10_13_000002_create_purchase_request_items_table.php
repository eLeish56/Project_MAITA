<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create the purchase_request_items table.
 *
 * Each purchase request can contain multiple line items. Each
 * line item stores the product/service description, the quantity
 * requested, and optional notes. Pricing is not included at the PR
 * stage — prices will be negotiated during PO creation.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            // Reference back to the parent purchase request
            $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('unit')->default('pcs');
            $table->integer('current_stock')->default(0);
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('item_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_items');
    }
};