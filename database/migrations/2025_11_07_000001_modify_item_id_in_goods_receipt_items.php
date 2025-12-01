<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom item_id sudah di-define dengan FK yang benar di file
        // 2025_10_13_000005_create_goods_receipt_items_table.php
        // Migration ini tidak perlu melakukan apa-apa.
        // (Dipertahankan untuk consistency dengan migration history)
    }

    public function down(): void
    {
        // Tidak ada yang perlu di-rollback
    }
};