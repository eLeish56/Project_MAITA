#!/bin/bash
# Script: Auto-start testing environment untuk marketplace auto-cancel
# Usage: bash scripts/test_auto_cancel_setup.sh

echo ""
echo "═══════════════════════════════════════════════════════════════"
echo "  SETUP TESTING AUTO-CANCEL MARKETPLACE ORDER (30 DETIK)"
echo "═══════════════════════════════════════════════════════════════"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ ERROR: artisan not found. Run from project root:"
    echo "   cd /path/to/laravelpos"
    exit 1
fi

echo "✅ Project root detected"
echo ""

# Show configuration
echo "📋 KONFIGURASI SAAT INI:"
echo "   ├─ Expired time: 30 detik"
echo "   ├─ Scheduler: everyMinute()"
echo "   ├─ Command: marketplace:expire-orders"
echo ""

# Instructions
echo "🚀 CARA TESTING (3 TERMINAL):"
echo ""
echo "   TERMINAL 1 - Start Laravel Server:"
echo "   ─────────────────────────────────────"
echo "   $ php artisan serve"
echo ""
echo "   Akses: http://127.0.0.1:8000"
echo ""
echo "   TERMINAL 2 - Monitor Scheduler:"
echo "   ─────────────────────────────────────"
echo "   $ php artisan schedule:work"
echo ""
echo "   TERMINAL 3 - Buat Test Order:"
echo "   ─────────────────────────────────────"
echo "   $ php scripts/create_test_order_expired.php"
echo ""
echo "   Tunggu 30 detik, lihat:"
echo "   1. Countdown timer di halaman order (real-time)"
echo "   2. Log di Terminal 2 saat scheduler jalankan"
echo "   3. Order status berubah ke 'expired'"
echo "   4. Tombol batalkan hilang"
echo "   5. Stok ter-restore"
echo ""

echo "🧪 ATAU MANUAL TRIGGER AUTO-EXPIRE:"
echo "   ─────────────────────────────────────"
echo "   $ php artisan marketplace:expire-orders"
echo ""

echo "📊 VERIFIKASI HASIL TEST:"
echo "   ─────────────────────────────────────"
echo "   $ php scripts/test_marketplace_expiry.php"
echo ""

echo "═══════════════════════════════════════════════════════════════"
echo ""
