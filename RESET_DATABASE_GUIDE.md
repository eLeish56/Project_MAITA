# 🔄 PANDUAN RESET DATABASE

## 📌 4 CARA RESET DATABASE

---

## **CARA 1: Artisan Migrate Fresh** ⭐ PALING MUDAH

Gunakan command bawaan Laravel untuk reset & recreate semua tables.

### Step 1: Buka PowerShell

```powershell
# Masuk ke folder project
cd D:\TA_25\TA_ALIF_TGL28-main
# atau folder mana pun tempat project Anda
```

### Step 2: Jalankan migrate fresh

```powershell
php artisan migrate:fresh
```

**Output yang benar:**
```
INFO  Preparing database.  

Creating migration table ................................. 45.23ms DONE

INFO  Running migrations.  

0001_01_01_000000_create_users_table ..................... 23.15ms DONE
0001_01_01_000001_create_cache_table ..................... 15.23ms DONE
...
[semua migrations berhasil]

INFO  Successfully reset database.
```

### Step 3 (OPTIONAL): Tambah sample data

```powershell
php artisan migrate:fresh --seed
```

**Penjelasan:**
- `--seed` = Jalankan seeder untuk insert sample data
- Jika tidak ada seeder, abaikan saja

---

## **CARA 2: Migrate Fresh dengan Force**

Jika ada error atau tabel corrupt, gunakan `--force`:

```powershell
php artisan migrate:fresh --force
```

**Kapan digunakan:**
- Database dalam state error
- Ada foreign key constraint issue
- Migration gagal di tengah jalan

---

## **CARA 3: Rollback Semua + Migrate Baru**

Jika ingin rollback dulu sebelum migrate:

```powershell
# Step 1: Rollback semua migration (arah kebalik)
php artisan migrate:rollback --step=999

# Step 2: Check status
php artisan migrate:status

# Step 3: Migrate dari awal
php artisan migrate
```

**Penjelasan:**
- `--step=999` = Rollback sampai 999 langkah (semua)
- Lebih aman jika ada data penting (tapi sama-sama dihapus)

---

## **CARA 4: Menggunakan MySQL Langsung (PHPMyAdmin / CLI)**

### **Option A: Via MySQL Command Line**

```powershell
# Step 1: Buka MySQL
mysql -u root -p

# Masukkan password Anda saat diminta

# Step 2: Di MySQL prompt, jalankan:
DROP DATABASE laravelpos;
CREATE DATABASE laravelpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;

# Step 3: Kembali ke PowerShell, jalankan:
php artisan migrate --seed
```

**Full script sekaligus (copy-paste ke PowerShell):**

```powershell
# Drop dan create database
mysql -u root -p -e "DROP DATABASE IF EXISTS laravelpos; CREATE DATABASE laravelpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Migrate
php artisan migrate --seed
```

### **Option B: Via PHPMyAdmin (GUI)**

1. Buka: `http://localhost/phpmyadmin`
2. Login dengan username/password MySQL Anda
3. Di sidebar, cari database `laravelpos`
4. Klik pada `laravelpos`
5. Tab **Operations** → klik **Drop** (pilih Agree jika diminta)
6. Buat database baru:
   - **Create database**: `laravelpos`
   - **Collation**: `utf8mb4_unicode_ci`
   - Klik **Create**
7. Kembali ke PowerShell:
   ```powershell
   php artisan migrate --seed
   ```

---

## 🎯 PILIHAN RESET BERDASARKAN SITUASI

| Situasi | Cara | Command |
|---------|------|---------|
| Reset normal | Cara 1 | `php artisan migrate:fresh` |
| Reset + sample data | Cara 1 | `php artisan migrate:fresh --seed` |
| Database error | Cara 2 | `php artisan migrate:fresh --force` |
| Ingin lihat step-by-step | Cara 3 | `migrate:rollback` + `migrate` |
| Lebih suka GUI | Cara 4B | PHPMyAdmin |
| Lebih suka command line | Cara 4A | MySQL CLI |

---

## ✅ VERIFICATION (Setelah Reset)

Pastikan reset berhasil:

### **Via Laravel:**

```powershell
# Check migration status
php artisan migrate:status

# Lihat table di database
php artisan tinker

# Di tinker prompt, jalankan:
Schema::getTables()
```

### **Via MySQL:**

```powershell
# Buka MySQL
mysql -u root -p -e "USE laravelpos; SHOW TABLES;"

# Output akan menampilkan semua table:
# users
# cache
# items
# categories
# ... dst
```

### **Via PHPMyAdmin:**

1. Buka: `http://localhost/phpmyadmin`
2. Login
3. Database `laravelpos` di sidebar
4. Lihat list tables di tab **Structure**

---

## 🆘 TROUBLESHOOTING

### **Error: "Access denied for user"**

```powershell
# Check .env file
notepad .env

# Pastikan:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=laravelpos
# DB_USERNAME=root
# DB_PASSWORD=yourpassword (atau kosong jika tidak ada password)
```

### **Error: "Unknown database 'laravelpos'"**

```powershell
# Database belum ada, create manual:
mysql -u root -p -e "CREATE DATABASE laravelpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Kemudian migrate:
php artisan migrate
```

### **Error: "SQLSTATE[HY000]"**

```powershell
# Jalankan dengan force flag:
php artisan migrate:fresh --force

# Atau reset manual:
php artisan cache:clear
php artisan config:clear
php artisan migrate:fresh
```

### **MySQL tidak running**

**Windows:**

```powershell
# Check apakah MySQL running
Get-Service MySQL80 | Select-Object Status

# Jika tidak running, start:
Start-Service MySQL80

# Atau via Services (GUI):
# Win + R → services.msc → cari MySQL → Start
```

---

## 📊 URUTAN RESET YANG BENAR

```
1. Backup database (OPTIONAL tapi recommended)
   ↓
2. Stop Laravel server (Ctrl+C jika running)
   ↓
3. Jalankan reset command
   - Pilih salah satu dari 4 cara di atas
   ↓
4. Verify bahwa reset berhasil
   - Check migration status
   - Check tables di MySQL
   ↓
5. Start Laravel server
   php artisan serve
   ↓
6. Test aplikasi
   http://127.0.0.1:8000
```

---

## 💾 BACKUP DATABASE SEBELUM RESET (RECOMMENDED)

### **Backup via Command Line:**

```powershell
# Export database ke file SQL
mysqldump -u root -p laravelpos > backup_laravelpos_$(Get-Date -Format 'yyyyMMdd_HHmmss').sql

# File akan tersimpan di folder project dengan nama:
# backup_laravelpos_20251127_143022.sql
```

### **Restore dari backup:**

```powershell
# Jika ingin restore nanti
mysql -u root -p laravelpos < backup_laravelpos_20251127_143022.sql
```

---

## 🚀 QUICK COPY-PASTE COMMANDS

### **Reset normal (recommended):**

```powershell
php artisan migrate:fresh
```

### **Reset + sample data:**

```powershell
php artisan migrate:fresh --seed
```

### **Reset force (jika ada error):**

```powershell
php artisan migrate:fresh --force
```

### **Reset via MySQL + migrate:**

```powershell
mysql -u root -p -e "DROP DATABASE IF EXISTS laravelpos; CREATE DATABASE laravelpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" ; php artisan migrate
```

---

## ✨ CHECKLIST RESET DATABASE

- [ ] Backup database (jika ada data penting)
- [ ] Stop Laravel server (Ctrl+C)
- [ ] Jalankan salah satu reset command di atas
- [ ] Wait sampai selesai (lihat "DONE")
- [ ] Verify: `php artisan migrate:status`
- [ ] Check tables di MySQL
- [ ] Start server: `php artisan serve`
- [ ] Test: buka `http://127.0.0.1:8000`
- [ ] ✅ SELESAI!

---

## 📝 CATATAN PENTING

1. **Reset akan menghapus semua data** - Backup dulu jika ada data penting
2. **Foreign key harus sesuai urutan** - Migration category harus sebelum items
3. **Character set harus utf8mb4** - Agar support emoji & karakter spesial
4. **Seeder optional** - Gunakan jika ingin sample data

---

**Gunakan Cara 1 jika tidak yakin - paling sederhana dan aman!**

