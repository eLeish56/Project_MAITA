<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('cost_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->integer('stock')->default(0);
            $table->string('picture')->nullable();
            $table->boolean('requires_expiry')->default(false); // Flag untuk item yang butuh expiry
            $table->timestamps();
        });

        // Tabel inventory_batches di file yang sama
        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('restrict');
            $table->decimal('qty_on_hand', 12, 2);
            $table->date('expiry_date')->nullable();
            $table->string('lot_code')->nullable();
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->string('location_code')->nullable();
            $table->enum('status', ['active', 'expired', 'quarantined'])->default('active');
            $table->timestamp('last_tx_at')->nullable();
            $table->timestamps();
            
            $table->index('lot_code');
            $table->index(['item_id', 'expiry_date']);
        });

        // inventory_movements is created in a dedicated migration (0001_01_01_000002_create_inventory_movements_table.php)
        // to avoid duplicate table definitions. The movements table schema used by the app
        // references `goods_receipt_item_id`, so it's defined in its own migration file.
    }

    public function down()
    {
        // inventory_movements is dropped by its dedicated migration's down() method.
        Schema::dropIfExists('inventory_batches');
        Schema::dropIfExists('items');
    }
};

<?php
$res = DB::select('SHOW CREATE TABLE inventory_movements');
print_r($res[0]);