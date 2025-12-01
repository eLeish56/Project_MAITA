<?php

/**
 * Database Reset Script
 * 
 * Script ini akan mereset database ke kondisi fresh (kosong dari 0)
 * Jalankan: php reset_database.php
 */

// Autoload Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║         DATABASE RESET - Fresh Start from 0                ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

try {
    // Cek koneksi database
    echo "🔍 Memeriksa koneksi database...\n";
    \DB::connection()->getPdo();
    echo "✅ Koneksi database berhasil!\n\n";

    // Confirm reset
    echo "⚠️  PERINGATAN: Operasi ini akan menghapus SEMUA data dari database!\n";
    echo "⚠️  Proses ini TIDAK BISA DIKEMBALIKAN!\n\n";
    
    // Jalankan migrate:fresh
    echo "🗑️  Menghapus dan membuat ulang semua tabel...\n";
    $exitCode = $kernel->call('migrate:fresh', ['--force' => true]);
    
    if ($exitCode === 0) {
        echo "\n✅ Database berhasil direset!\n";
        echo "✅ Semua tabel dihapus dan dibuat ulang dari migration.\n";
        echo "✅ Database sekarang kosong dan siap digunakan dari 0.\n";
        echo "✅ Auto-increment reset ke 1.\n";
        
        // Optional: Jalankan seeder jika ada
        echo "\n📊 Database kini kosong. Anda bisa menjalankan:\n";
        echo "   php artisan migrate:fresh --seed  (jika ingin data dummy)\n";
        echo "   atau langsung gunakan sistem\n";
    } else {
        echo "\n❌ Reset gagal dengan exit code: $exitCode\n";
    }

} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "⚠️  Pastikan MySQL server berjalan dan .env configured dengan benar.\n";
    exit(1);
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "Selesai! Silakan refresh aplikasi Anda.\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
