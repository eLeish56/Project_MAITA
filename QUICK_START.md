# 🚀 QUICK SETUP - 10 Langkah Cepat

**Durasi**: ~15-20 menit (tergantung internet)

---

## ✅ Step-by-Step Commands

Jalankan perintah ini satu per satu di PowerShell:

### **1️⃣ Clone/Buka Folder Project**
```powershell
cd D:\Projects\TA_ALIF_TGL28-main
```

### **2️⃣ Install Composer Dependencies**
```powershell
composer install
```

### **3️⃣ Copy Environment File**
```powershell
Copy-Item .env.example -Destination .env
```

### **4️⃣ EDIT .env File**
```powershell
notepad .env
```

**Ubah bagian DATABASE ke:**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravelpos
DB_USERNAME=root
DB_PASSWORD=
```

**Simpan (Ctrl+S) dan tutup**

### **5️⃣ Generate Application Key**
```powershell
php artisan key:generate
```

### **6️⃣ Buat Database di MySQL**
```powershell
mysql -u root -p
```

**Di MySQL prompt, ketik:**
```sql
DROP DATABASE IF EXISTS laravelpos;
CREATE DATABASE laravelpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### **7️⃣ Fresh Migrate Database**
```powershell
php artisan migrate:fresh
```

### **8️⃣ Seed Data Awal**
```powershell
php artisan db:seed
```

### **9️⃣ Install npm & Build Assets**
```powershell
npm install
npm run build
```

### **🔟 Buat Storage Link**
```powershell
php artisan storage:link
```

---

## ✨ READY! Jalankan Server

```powershell
php artisan serve
```

**Buka browser:**
```
http://127.0.0.1:8000/transaction
```

---

## 🔐 Login Default

- **Email**: `admin@example.com`
- **Password**: `password`

---

## 🔄 Jika Ingin Reset Data (Fresh Start)

```powershell
php artisan migrate:fresh --seed
```

---

## ⚠️ Common Issues & Quick Fix

| Problem | Solution |
|---------|----------|
| "MySQL connection error" | Pastikan MySQL service running. Di Windows: `Services` > cari MySQL > Start |
| "php: command not found" | PHP belum di PATH. Reinstall PHP atau add ke PATH |
| "composer: command not found" | Install Composer dari https://getcomposer.org/ |
| "Key already exists" | Normal, proceed ke langkah berikutnya |
| Database error saat migrate | Check .env DB credentials sudah benar |

---

## 📋 File Environment (.env)

Jika perlu reference, berikut sedikit penjelasan:

```env
APP_NAME="Teaching Factory POS"      # Nama aplikasi
APP_ENV=local                        # local/production
APP_DEBUG=true                       # true saat development
APP_URL=http://127.0.0.1:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1                   # Alamat database (localhost)
DB_PORT=3306                        # Default port MySQL
DB_DATABASE=laravelpos              # Nama database
DB_USERNAME=root                    # Username MySQL
DB_PASSWORD=                        # Password (kosong = no password)
```

---

## 📁 Struktur Folder Penting

```
project-root/
├── app/
│   ├── Models/          # Database models
│   └── Http/Controllers/# Controllers
├── database/
│   ├── migrations/      # Database schema
│   └── seeders/         # Initial data
├── resources/
│   └── views/           # Blade templates
├── storage/
│   └── logs/            # Error logs
└── .env                 # Configuration file
```

---

## ✅ Verify Installation

Setelah `php artisan serve`, jika melihat:
```
Laravel development server started at [http://127.0.0.1:8000]
```

Maka semuanya sudah OK! 🎉

---

**Total Langkah**: 10 steps
**Estimated Time**: 15-20 minutes
**Status**: Ready to use ✅

