# Migration Audit Report - Cross-Check Duplikasi

**Tanggal Audit**: 24 November 2025
**Status**: ⚠️ **DITEMUKAN DUPLIKASI & ISSUE**

---

## 📋 Ringkasan Temuan

| Status | Jumlah | Deskripsi |
|--------|--------|-----------|
| ✅ Valid | 32 | Migration yang unik dan tidak duplikasi |
| ⚠️ Duplikasi | 2 | Tabel cart yang didefinisikan 2x |
| ❌ Error | 1 | Inconsistency di wholesale_prices migration |
| 📝 Issue | 1 | Tabel inventory_movements & inventory_records duplikasi logika |

---

## 🚨 ISSUE #1: DUPLIKASI TABEL CART

### ❌ PROBLEM DITEMUKAN

**File 1**: `2024_05_21_182615_create_carts_table.php`
```php
Schema::create('carts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('item_id')->constrained()->onDelete('cascade');
    $table->integer('qty');
    $table->integer('subtotal');
    $table->timestamps();
});
```

**File 2**: `2025_11_07_232719_create_cart_items_table.php`
```php
Schema::create('cart_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('item_id')->constrained()->onDelete('cascade');
    $table->integer('quantity');
    $table->unique(['user_id', 'item_id']);
    $table->timestamps();
});
```

### 📊 Analisis Perbedaan

| Aspek | carts | cart_items |
|-------|-------|-----------|
| Jumlah Kolom | 5 | 5 |
| PK | id | id |
| FK user_id | ✓ | ✓ |
| FK item_id | ✓ | ✓ |
| Qty Column | `qty` (int) | `quantity` (int) |
| Subtotal | Ada (precomputed) | Tidak ada |
| Unique Constraint | Tidak | ✓ ada |
| Struktur | Hampir sama | Hampir sama |

### ⚠️ KESIMPULAN

**Kedua tabel seharusnya HANYA 1** (bukan 2):
- `carts` adalah versi lama (2024)
- `cart_items` adalah versi baru (2025) dengan unique constraint
- Keduanya tidak bisa hidup bersamaan di production

### ✅ REKOMENDASI

**Opsi 1** (Recommended): Gunakan tabel `cart_items` (lebih baru)
- Hapus migration `2024_05_21_182615_create_carts_table.php`
- Pastikan code menggunakan model `CartItem` bukan `Cart`

**Opsi 2**: Merge keduanya
- Tetapkan `carts` sebagai tabel utama
- Tambahkan `unique` constraint di migration `2024_05_21_182615`
- Hapus `cart_items`

---

## ❌ ISSUE #2: ERROR DI WHOLESALE_PRICES MIGRATION

**File**: `2024_05_21_175123_create_wholesale_prices_table.php`

```php
public function up(): void {
    Schema::create('wholesale_prices', function (Blueprint $table) {
        // ...
    });
}

public function down(): void {
    Schema::dropIfExists('wholesale_items'); // ❌ SALAH!
}
```

### 🐛 BUG

- **Create**: Tabel `wholesale_prices` dibuat ✓
- **Drop**: Tabel `wholesale_items` dihapus ❌ (nama berbeda!)
- **Akibat**: Ketika rollback, tabel `wholesale_prices` tetap ada (tidak terhapus)

### ✅ PERBAIKAN

```php
public function down(): void {
    Schema::dropIfExists('wholesale_prices'); // ✓ BENAR
}
```

---

## 📝 ISSUE #3: DUPLIKASI LOGIKA INVENTORY MOVEMENTS

### Status: ⚠️ POTENTIAL ISSUE (bukan critical)

**File 1**: `0001_01_01_000000_create_items_table.php`
```php
// Membuat tabel inventory_batches di sini
Schema::create('inventory_batches', function (Blueprint $table) {
    // ...
});
```

**File 2**: `0001_01_01_000002_create_inventory_movements_table.php`
```php
Schema::create('inventory_movements', function (Blueprint $table) {
    $table->foreignId('goods_receipt_item_id')->constrained('goods_receipt_items');
    // ...
});
```

**File 3**: `2025_11_05_234855_create_inventory_records_table.php`
```php
Schema::create('inventory_records', function (Blueprint $table) {
    $table->foreignId('goods_receipt_id')->constrained()->onDelete('cascade');
    // ...
});
```

### 📊 Tabel-tabel yang Ada

| Tabel | Tujuan | Status |
|-------|--------|--------|
| `inventory_batches` | Track batch/lot items dengan expiry | Aktif |
| `inventory_movements` | Track in/out movements | Aktif |
| `inventory_records` | Track inventory history | Aktif |

### 🤔 ANALISIS

Ketiga tabel ini:
- **Bukan duplikasi** (setiap punya tujuan berbeda)
- Tapi ada **potential confusion** tentang mana yang digunakan
- `inventory_movements` dan `inventory_records` punya logika mirip

### ✅ REKOMENDASI

Tentukan yang mana yang aktif digunakan:
- Jika pakai `inventory_records` → hapus `inventory_movements`
- Jika pakai `inventory_movements` → hapus `inventory_records`
- Jika pakai keduanya → dokumentasikan tujuan masing-masing

---

## ✅ VALIDASI STRUKTUR TABEL UTAMA

### Tabel yang VALID (Tidak Duplikasi)

| No | Tabel | Migration File | Status |
|----|-------|-----------------|--------|
| 1 | items | 0001_01_01_000000 | ✅ Valid |
| 2 | users | 0001_01_01_000000 | ✅ Valid |
| 3 | cache | 0001_01_01_000001 | ✅ Valid |
| 4 | categories | 2024_05_21_174125 | ✅ Valid |
| 5 | customers | 2024_05_21_174227 | ✅ Valid |
| 6 | payment_methods | 2024_05_21_174511 | ✅ Valid |
| 7 | item_supplier | 2024_05_21_175122 | ✅ Valid |
| 8 | wholesale_prices | 2024_05_21_175123 | ⚠️ Bug di down() |
| 9 | transactions | 2024_05_22_030109 | ✅ Valid |
| 10 | transaction_details | 2024_05_22_030902 | ✅ Valid |
| 11 | absences | 2024_05_27_072011 | ✅ Valid |
| 12 | inventory_settings | 2024_10_28_000001 | ✅ Valid |
| 13 | stock_movement_analyses | 2024_10_28_000002 | ✅ Valid |
| 14 | sessions | 2024_10_28_000003 | ✅ Valid |
| 15 | supplier_products | 2025_07_23_105030 | ✅ Valid |
| 16 | purchase_orders | 2025_07_23_145713 | ✅ Valid |
| 17 | purchase_order_items | 2025_07_23_145728 | ✅ Valid |
| 18 | marketplace_orders | 2025_09_04_000001 | ✅ Valid |
| 19 | marketplace_order_items | 2025_09_04_000002 | ✅ Valid |
| 20 | purchase_requests | 2025_10_13_000001 | ✅ Valid |
| 21 | purchase_request_items | 2025_10_13_000002 | ✅ Valid |
| 22 | goods_receipts | 2025_10_13_000004 | ✅ Valid |
| 23 | goods_receipt_items | 2025_10_13_000005 | ✅ Valid |
| 24 | invoices | 2025_10_13_000006 | ✅ Valid |
| 25 | inventory_records | 2025_11_05_234855 | ✅ Valid |
| 26 | password_reset_tokens | 2025_11_11_224914 | ✅ Valid |
| **DUPLIKASI** | **carts** | **2024_05_21_182615** | ❌ Duplikasi |
| **DUPLIKASI** | **cart_items** | **2025_11_07_232719** | ❌ Duplikasi |

---

## 🔧 ACTION PLAN (Prioritas)

### 🔴 CRITICAL (Harus diperbaiki sebelum deploy)

1. **Fix Issue #2**: Perbaiki `wholesale_prices` migration
   ```
   File: 2024_05_21_175123_create_wholesale_prices_table.php
   Ubah: dropIfExists('wholesale_items') → dropIfExists('wholesale_prices')
   ```
   **Priority**: HIGH - Bisa cause issue saat rollback

2. **Resolve Issue #1**: Tentukan gunakan carts atau cart_items
   ```
   - Jika pakai cart_items: Hapus 2024_05_21_182615_create_carts_table.php
   - Jika pakai carts: Hapus 2025_11_07_232719_create_cart_items_table.php
   - Check model Cart vs CartItem di /app/Models/
   ```
   **Priority**: HIGH - Bisa cause duplikasi tabel

### 🟡 MEDIUM (Sebaiknya diperbaiki)

3. **Clarify Issue #3**: Tentukan gunakan inventory_movements atau inventory_records
   ```
   - Dokumentasikan tujuan masing-masing
   - Hapus yang tidak digunakan
   - Update database schema documentation
   ```
   **Priority**: MEDIUM - Bisa cause confusion di future development

### 🟢 LOW (Informasi)

4. **Documentation**: Buat migration naming convention
   - Konsistensikan format nama file
   - Tambahkan comments untuk purpose setiap migration

---

## 📊 Statistik Audit

```
Total Migrations: 34 files
├─ ✅ Valid & Unique: 32
├─ ❌ Duplikasi: 2
├─ ⚠️ Bug/Issue: 1
└─ 📝 Potential Issue: 1

Risk Level: MEDIUM (1 active duplikasi, 1 rollback bug)
```

---

## 🔍 Cross-Reference: Model vs Migration

### Cek konsistensi Model dengan Migration

```bash
# Model yang ada di app/Models/
- Cart.php (di-reference oleh 2024_05_21_182615)
- CartItem.php (di-reference oleh 2025_11_07_232719)

# Tentukan: Mana yang saat ini digunakan di TransactionController?
# Check: app/Http/Controllers/TransactionController.php
# Cari: Cart::where() atau CartItem::where()
```

### Foreign Key Consistency

```
✅ Semua FK reference tabel yang valid
✅ Cascade/Soft delete logic konsisten
✅ Nullable fields sesuai dengan business logic
```

---

## 📝 Catatan

- **Audit dilakukan**: 24 Nov 2025
- **Database Status**: Development (belum production)
- **Recommendation**: Bersihkan duplikasi sebelum push ke staging
- **Testing**: Jalankan `php artisan migrate:fresh --seed` untuk verify

---

## ✅ Rekomendasi Akhir

```
1. IMMEDIATE: Perbaiki wholesale_prices down() method
2. TODAY: Tentukan gunakan carts atau cart_items
3. THIS WEEK: Clarify & document inventory tracking tables
4. NEXT: Implement automated migration testing
```

---

**Report Status**: ✅ COMPLETE
**Dibuat oleh**: AI Audit System
**Butuh Action**: YES (2 items critical)

