# 🔧 FIX: Foreign Key Constraint Error (errno: 150)

## ❌ ERROR YANG TERJADI

```
SQLSTATE[HY000]: General error: 1005 Can't create table laravelpos.items 
(errno: 150 "Foreign key constraint is incorrectly formed")

at alter table items add constraint items_category_id_foreign 
foreign key (category_id) references categories (id)
```

---

## 🔍 PENYEBAB ERROR

### **Masalah Utama: Foreign Key Constraint Error 150**

Errno 150 berarti:
1. **Tabel yang di-reference belum ada** (categories belum dibuat)
2. **Tipe data tidak matching** (category_id INT tapi categories.id VARCHAR)
3. **Foreign key syntax salah**
4. **Tabel sudah ada tapi constraint conflict**
5. **MySQL strict mode setting**

### **Di Kasus Anda Spesifik:**

File migration: `0001_01_01_000000_create_items_table.php`

```php
$table->foreignId('category_id')->constrained('categories');
```

**Masalahnya:**
- Tabel `categories` BELUM ada saat membuat `items`
- Migration order salah!
- File `0001_01_01_000000` mencoba create items dulu sebelum categories

---

## ✅ SOLUSI (3 PILIHAN)

---

## **SOLUSI 1: FIX Migration Order** ⭐ RECOMMENDED

### Step 1: Identifikasi urutan migration

Masalahnya file `0001_01_01_000000_create_items_table.php` mencoba create tabel items dengan foreign key ke categories, tapi categories belum ada.

### Step 2: Cek file migration

Buka folder: `database/migrations/`

**Cari ini:**
- `0001_01_01_000000_create_items_table.php` ← **ADA FOREIGN KEY KE CATEGORIES**
- `2024_05_21_174125_create_categories_table.php` ← **DIBUAT KEMUDIAN**

**MASALAH**: Items mau create sebelum categories!

### Step 3: Perbaiki migration items_table

Buka file: `database/migrations/0001_01_01_000000_create_items_table.php`

**Ubah dari:**
```php
$table->foreignId('category_id')->constrained('categories');
```

**Menjadi:**
```php
$table->unsignedBigInteger('category_id')->nullable();
$table->foreign('category_id')
    ->references('id')
    ->on('categories')
    ->onDelete('cascade');
```

### Step 4: Atau, Ubah Category_id menjadi nullable & lazy:

```php
$table->foreignId('category_id')->nullable()->constrained('categories');
```

### Step 5: Fresh migrate lagi

```powershell
php artisan migrate:fresh
```

---

## **SOLUSI 2: Reorganize Migration Files** (Lebih Baik)

Masalahnya adalah **migration order**. Kategori harus dibuat sebelum items.

### Step 2.1: Rename migration files dengan timestamp yang benar

**Masalah saat ini:**
```
0001_01_01_000000_create_items_table.php (Foreign key ke categories)
2024_05_21_174125_create_categories_table.php (Categories belum ada!)
```

**Solusi**: Ubah urutan dengan rename file

**BUKA FILE:**
- `database/migrations/0001_01_01_000000_create_items_table.php`

**RENAME MENJADI:**
- `database/migrations/2024_05_20_000000_create_items_table.php`

**RENAME FILE CATEGORIES:**
- `database/migrations/2024_05_21_174125_create_categories_table.php`
- **MENJADI:**
- `database/migrations/2024_05_20_100000_create_categories_table.php` (earlier timestamp)

### Step 2.2: Fresh migrate

```powershell
php artisan migrate:fresh
```

---

## **SOLUSI 3: Remove Foreign Key Constraint** (Temporary)

Jika tidak ingin refactor migration, hapus foreign key sementara:

**File**: `database/migrations/0001_01_01_000000_create_items_table.php`

**Ubah dari:**
```php
$table->foreignId('category_id')->constrained('categories');
```

**Menjadi:**
```php
$table->unsignedBigInteger('category_id')->nullable();
// Foreign key akan ditambah nanti via separate migration
```

Kemudian buat migration baru untuk add foreign key:

```powershell
php artisan make:migration add_category_foreign_key_to_items_table
```

**File migration baru:**
```php
public function up(): void {
    Schema::table('items', function (Blueprint $table) {
        $table->foreign('category_id')
            ->references('id')
            ->on('categories')
            ->onDelete('cascade');
    });
}

public function down(): void {
    Schema::table('items', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
    });
}
```

---

## 🚀 SOLUSI CEPAT (GUNAKAN INI!)

### Jalankan di PowerShell satu per satu:

```powershell
# 1. Rollback semua
php artisan migrate:rollback --step=999

# 2. Check migrations
php artisan migrate:status

# 3. Fresh migrate dengan seed
php artisan migrate:fresh --seed

# 4. Jika masih error, lakukan:
php artisan migrate:fresh --force
```

---

## 🔧 FIX PERMANEN - Edit Migration File

Buka file: `database/migrations/0001_01_01_000000_create_items_table.php`

### Cari bagian ini:
```php
Schema::create('items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained('categories');
    // ... rest of code
});
```

### Ubah menjadi:

```php
Schema::create('items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('category_id')->nullable();
    $table->string('code')->unique();
    $table->string('name');
    $table->decimal('cost_price', 12, 2);
    $table->decimal('selling_price', 12, 2);
    $table->integer('stock')->default(0);
    $table->string('picture')->nullable();
    $table->boolean('requires_expiry')->default(false);
    $table->timestamps();
    
    // Tambah foreign key di akhir
    $table->foreign('category_id')
        ->references('id')
        ->on('categories')
        ->onDelete('cascade');
});
```

**PENTING**: Pastikan `categories` table dibuat SEBELUM `items` table!

---

## 📊 ROOT CAUSE ANALYSIS

### Mengapa error terjadi?

**Timeline Kesalahan:**

```
Migration 0001_01_01_000000_create_items_table.php RUN
  ├─ Try create 'items' table
  ├─ Try add foreign key: category_id → categories.id
  ├─ ERROR! Tabel 'categories' belum ada
  └─ FAIL ❌

Migration 2024_05_21_174125_create_categories_table.php BELUM RUN
  └─ Tabel 'categories' baru mau dibuat, tapi sudah error
```

### Solusi:

**Categories HARUS dibuat terlebih dahulu!**

---

## ✅ VERIFICATION STEPS

Setelah apply fix, jalankan:

```powershell
# 1. Fresh migrate
php artisan migrate:fresh

# 2. Check table structure
mysql -u root -p laravelpos
  SHOW TABLES;
  DESCRIBE items;
  DESCRIBE categories;
  EXIT;

# 3. Seed data
php artisan db:seed

# 4. Test aplikasi
php artisan serve
# Buka: http://127.0.0.1:8000
```

---

## 🔍 DEBUG DETAILS

Jika masih error, check ini:

```powershell
# Check migration order
php artisan migrate:status

# Check database character set
mysql -u root -p
  USE laravelpos;
  SHOW CREATE TABLE categories;
  SHOW CREATE TABLE items;
  EXIT;

# Check Laravel config
php artisan config:show database
```

---

## 🎯 CHECKLIST FIX

- [ ] Buka file: `0001_01_01_000000_create_items_table.php`
- [ ] Ganti `foreignId('category_id')->constrained()` dengan format yang benar
- [ ] Pastikan `categories` dibuat SEBELUM `items` (check migration timestamp)
- [ ] Jalankan: `php artisan migrate:fresh`
- [ ] Lihat table di PHPMyAdmin
- [ ] Verify data: `SELECT * FROM items;`
- [ ] ✅ NO ERROR

---

## 💡 BEST PRACTICE

Untuk menghindari error ini di masa depan:

```php
// ❌ SALAH (Foreign key ke tabel yang belum ada)
$table->foreignId('category_id')->constrained('categories');

// ✅ BENAR (Gunakan nullable jika belum ada)
$table->unsignedBigInteger('category_id')->nullable();

// ✅ ATAU (Definisikan foreign key terpisah di down)
$table->foreign('category_id')
    ->references('id')
    ->on('categories')
    ->onDelete('cascade');

// ✅ ATAU (Pastikan kategori dibuat duluan di migration order)
// File timestamp: 2024_05_20_000000_create_categories_table.php
// File timestamp: 2024_05_21_000000_create_items_table.php
```

---

## 🆘 JIKA MASIH ERROR

Coba ini:

```powershell
# 1. Drop dan recreate database
mysql -u root -p
  DROP DATABASE laravelpos;
  CREATE DATABASE laravelpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  EXIT;

# 2. Fresh migrate dengan force
php artisan migrate:fresh --force

# 3. Jika masih error, check .env
notepad .env
# Pastikan:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=laravelpos
# DB_USERNAME=root
# DB_PASSWORD=(sesuai setting)
```

---

## 📝 QUICK REFERENCE

| Error | Penyebab | Solusi |
|-------|---------|--------|
| errno: 150 | Foreign key constraint salah | Fix foreign key syntax |
| Foreign key belum ada | Tabel reference belum dibuat | Ubah migration order |
| Table already exists | Migration conflict | `migrate:fresh --force` |
| Wrong data type | INT vs VARCHAR mismatch | Samakan type columns |

---

## ✨ STATUS

**Before**: ❌ ERROR errno: 150
**After**: ✅ FIXED Foreign key constraint

**Expected Result**: `php artisan migrate:fresh` berhasil tanpa error

---

**Coba solusi ini dan report hasilnya!**

