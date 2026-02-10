# NARASI USECASE DIAGRAM
## Sistem POS Terintegrasi dengan Marketplace & Procurement

---

## 1. CUSTOMER (Pelanggan Marketplace)

### Narasi Aktor:
Customer adalah pengguna publik yang menggunakan platform marketplace online untuk berbelanja produk. Mereka melakukan registrasi mandiri, browsing produk, memanfaatkan fitur cart, dan melakukan pembayaran online untuk setiap pesanan.

### Use Case Utama:

**UC-C1: Register & Login**
- Customer mendaftar akun baru dengan email dan password
- Customer login dengan credentials mereka
- Customer dapat logout dari sistem

**UC-C2: Browse Marketplace**
- Customer melihat halaman marketplace utama
- Customer dapat mencari/filter produk berdasarkan kategori
- Customer melihat detail produk (harga, gambar, deskripsi, stok)

**UC-C3: Manage Shopping Cart**
- Customer menambahkan produk ke keranjang
- Customer dapat melihat isi keranjang
- Customer dapat mengubah jumlah item
- Customer dapat menghapus item dari keranjang
- Customer dapat mengosongkan keranjang sekaligus

**UC-C4: Checkout & Payment**
- Customer melanjutkan ke halaman checkout
- Customer mengkonfirmasi data pengiriman
- Customer memilih metode pembayaran online
- Customer melakukan pembayaran dan pesanan terproses

**UC-C5: Order Management**
- Customer melihat daftar pesanan mereka
- Customer melihat detail pesanan (item, status, harga)
- Customer melacak status pesanan (pending → processing → completed)

**UC-C6: Profile Management**
- Customer melihat profil pribadi
- Customer mengubah data profil (nama, email, phone, address)

---

## 2. CASHIER (Staf POS)

### Narasi Aktor:
Cashier adalah staf counter toko yang mengelola dua fungsi utama: (1) Transaksi penjualan langsung di POS dengan customer yang datang ke toko, dan (2) Pemrosesan pesanan online/marketplace yang sudah diterima pembayaran dari customer.

### Use Case Utama:

**UC-K1: Manage POS Transaction**
- Cashier membuat transaksi penjualan baru
- Cashier menambahkan item ke cart dengan cek stok real-time
- Cashier dapat mengubah jumlah item atau menghapus item dari cart
- Cashier mengosongkan cart jika diperlukan
- Cashier menyelesaikan transaksi dengan memilih metode pembayaran
- Cashier mencetak receipt/struk untuk customer
- Cashier dapat melihat daftar transaksi yang sudah diproses

**UC-K2: Process Online Orders**
- Cashier melihat daftar pesanan online yang status pembayarannya pending
- Cashier melihat detail items dalam pesanan online
- Cashier memproses pembayaran pesanan (mengubah status dari debt menjadi paid)
- Cashier mengonfirmasi pesanan sudah diproses

**UC-K3: Process Marketplace Orders**
- Cashier melihat daftar pesanan dari marketplace yang belum diproses
- Cashier melihat detail items dalam pesanan marketplace
- Cashier memproses pembayaran pesanan marketplace
- Sistem mencatat pesanan sebagai completed

**UC-K4: View Inventory Items**
- Cashier dapat melihat detail item dari inventory untuk memastikan ketersediaan

---

## 3. ADMIN (Administrator Sistem)

### Narasi Aktor:
Admin adalah pengguna dengan akses tinggi yang bertanggung jawab atas manajemen data master dalam sistem. Admin mengelola master data produk, kategori, supplier, serta customer yang terdaftar sebagai pengguna internal sistem.

### Use Case Utama:

**UC-A1: Manage Products (Items)**
- Admin melihat daftar semua produk/item dalam inventory
- Admin membuat produk baru (dengan nama, harga regular, harga wholesale, deskripsi, gambar)
- Admin mengubah informasi produk (nama, harga, deskripsi)
- Admin mengupload/mengubah gambar produk
- Admin menghapus produk dari sistem
- Admin melihat harga regular dan wholesale untuk setiap produk

**UC-A2: Manage Categories**
- Admin melihat daftar kategori produk
- Admin membuat kategori baru
- Admin mengubah informasi kategori
- Admin menghapus kategori (jika tidak ada produk yang terkait)

**UC-A3: Manage Suppliers**
- Admin melihat daftar supplier terdaftar
- Admin membuat supplier baru dengan informasi lengkap
- Admin mengubah data supplier (nama, contact, address, payment terms)
- Admin menghapus supplier dari sistem

**UC-A4: Manage Customers**
- Admin melihat daftar customer yang terdaftar sebagai user internal
- Admin membuat customer baru (untuk internal use)
- Admin mengubah data customer
- Admin menghapus customer dari sistem

**UC-A5: View & Export Sales Reports**
- Admin melihat laporan transaksi penjualan
- Admin melakukan filter laporan berdasarkan tanggal, payment method, dll
- Admin melihat detail transaksi tertentu
- Admin mengexport laporan penjualan ke format Excel/PDF

---

## 4. SUPERVISOR (Pimpinan Operasional)

### Narasi Aktor:
Supervisor adalah role tertinggi yang memiliki kontrol penuh atas operasional sistem. Supervisor mengelola semua aspek: manajemen user internal, master data inventory, workflow procurement kompleks, monitoring stok, dan laporan comprehensive.

### Use Case Utama:

**UC-S1: Manage Internal Users**
- Supervisor melihat daftar semua user internal (admin, cashier, supervisor)
- Supervisor membuat user baru dengan role dan akses yang sesuai
- Supervisor mengubah data user (nama, username, email, password, position)
- Supervisor mengaktifkan/menonaktifkan akun user
- Supervisor menghapus user dari sistem
- Supervisor dapat reset password user

**UC-S2: Manage Inventory Master Data**
- Supervisor melakukan semua operasi CRUD untuk items (sama seperti admin)
- Supervisor melakukan semua operasi CRUD untuk categories
- Supervisor melakukan semua operasi CRUD untuk suppliers
- Supervisor mengelola produk dari setiap supplier

**UC-S3: Manage Batch & Expiry Tracking**
- Supervisor melihat daftar batch produk dengan info tanggal kadaluarsa
- Supervisor melihat batch untuk item tertentu
- Supervisor update status kadaluarsa batch
- Supervisor memonitor produk yang akan kadaluarsa
- Supervisor dapat melakukan action terhadap batch expired

**UC-S4: Analyze Stock Movement**
- Supervisor melihat dashboard analisis pergerakan stok
- Supervisor melihat daftar produk fast-moving (penjualan cepat)
- Supervisor melihat daftar produk slow-moving (penjualan lambat)
- Supervisor menganalisis trend pergerakan stok
- Supervisor mengkonfigurasi parameter perhitungan movement
- Supervisor mengexport hasil analisis stok
- Supervisor menggunakan insight untuk decision making procurement

**UC-S5: Create Purchase Request (PR)**
- Supervisor membuat Purchase Request baru ketika stok menipis
- Supervisor memilih supplier dan produk yang akan dipesan
- Supervisor menentukan jumlah pesanan untuk setiap item
- Supervisor melihat daftar semua PR yang telah dibuat
- Supervisor melihat detail PR lengkap dengan items
- Supervisor menghasilkan dokumen PDF untuk PR

**UC-S6: Approve/Reject Purchase Request**
- Supervisor melihat daftar PR yang menunggu persetujuan
- Supervisor mereview detail PR sebelum approval
- Supervisor menyetujui PR (approval) jika sesuai kebijakan
- Supervisor menolak PR (reject) dengan alasan jika tidak sesuai
- Supervisor memberikan feedback kepada pembuat PR

**UC-S7: Create Purchase Order (PO)**
- Supervisor membuat PO dari PR yang sudah disetujui
- Supervisor dapat membuat PO langsung (direct PO) dari PR
- Supervisor melihat daftar PO yang telah dibuat
- Supervisor melihat detail PO dengan items dan supplier
- Supervisor dapat mengedit harga item di PO sebelum dikirim
- Supervisor mengkonfirmasi harga PO setelah review
- Supervisor menandai PO sebagai "sent" setelah dikirim ke supplier
- Supervisor menghasilkan dokumen PDF untuk PO

**UC-S8: Create & Process Goods Receipt (GR)**
- Supervisor membuat Goods Receipt ketika barang tiba dari supplier
- Supervisor mencatat item yang diterima (jumlah, kondisi)
- Supervisor melihat daftar semua Goods Receipts
- Supervisor melihat detail GR dengan item yang diterima
- Supervisor download dokumen GR (PDF)

**UC-S9: Create Inventory Records**
- Supervisor membuat Inventory Record dari Goods Receipt
- Supervisor mencatat item diterima ke dalam stok inventory
- Supervisor melihat daftar inventory records yang telah dibuat
- Supervisor melihat detail record tertentu

**UC-S10: Manage Invoices from Supplier**
- Supervisor membuat Invoice record dari PO yang barangnya sudah diterima
- Supervisor melihat daftar semua invoice dari supplier
- Supervisor melihat detail invoice dengan items dan jumlah pembayaran
- Supervisor mengupload file invoice dari supplier
- Supervisor mengupload bukti pembayaran invoice
- Supervisor menandai invoice sebagai "paid" setelah pembayaran dilakukan
- Supervisor download file invoice
- Supervisor download bukti pembayaran

**UC-S11: View Procurement Dashboard & Reports**
- Supervisor melihat dashboard overview workflow procurement
- Supervisor melihat status PR, PO, GR, dan Invoice dalam satu dashboard
- Supervisor melihat laporan pembelian lengkap dari semua supplier
- Supervisor melihat detail laporan pembelian tertentu
- Supervisor mengexport laporan pembelian ke file

**UC-S12: Manage Payment Methods**
- Supervisor melihat daftar metode pembayaran yang tersedia
- Supervisor menambahkan metode pembayaran baru
- Supervisor mengubah konfigurasi metode pembayaran
- Supervisor menghapus metode pembayaran yang tidak digunakan

**UC-S13: Manage Absence (Cuti/Izin Karyawan)**
- Supervisor melihat daftar catatan cuti/izin karyawan
- Supervisor membuat catatan cuti/izin baru untuk karyawan
- Supervisor mengubah catatan cuti/izin
- Supervisor menghapus catatan cuti/izin

**UC-S14: View Comprehensive Transaction Reports**
- Supervisor melihat laporan lengkap semua transaksi penjualan
- Supervisor melakukan filter laporan dengan berbagai kriteria (tanggal, metode pembayaran, cashier, dll)
- Supervisor mencari transaksi tertentu
- Supervisor mengexport laporan transaksi

**UC-S15: View Own Profile & Settings**
- Supervisor melihat profil pribadi
- Supervisor mengubah data profil pribadi

---

## 5. SUPPLIER (Pihak Eksternal)

### Narasi Aktor:
Supplier adalah pihak eksternal yang menyediakan produk/barang kepada toko. Supplier tidak memiliki akses langsung ke sistem, tetapi berinteraksi melalui dokumen dan transaksi yang diinisiasi oleh Supervisor.

### Use Case Utama:

**UC-S1: Maintain Product Catalog**
- Supplier memberikan informasi produk mereka (nama, harga, spesifikasi, stok)
- Data produk supplier disimpan dalam sistem database
- Supervisor dapat mengakses dan menggunakan daftar produk supplier untuk membuat PR/PO

**UC-S2: Receive Purchase Order**
- Supervisor mengirimkan dokumen PO kepada supplier (via email atau fisik)
- Supplier menerima PO dengan detail items dan jumlah pesanan

**UC-S3: Deliver Goods**
- Supplier menyiapkan barang sesuai PO
- Supplier mengirimkan barang dengan dokumen pendukung (packing slip, delivery note)
- Barang tiba di toko dan dicatat dalam Goods Receipt

**UC-S4: Submit Invoice**
- Supplier membuat invoice untuk pengiriman barang
- Supplier mengirimkan invoice ke toko (via email atau fisik)
- Invoice diupload ke sistem oleh supervisor

**UC-S5: Receive Payment**
- Supervisor memproses pembayaran sesuai invoice
- Supplier menerima pembayaran dan transaksi selesai

---

## 6. OWNER (Pemilik Bisnis) - OPTIONAL

### Narasi Aktor:
Owner adalah pemilik bisnis yang membutuhkan visibility terhadap keseluruhan kinerja operasional dan keuangan toko dalam level strategis.

### Use Case Utama:

**UC-O1: View Dashboard & KPIs**
- Owner melihat dashboard utama dengan ringkasan KPI penjualan
- Owner melihat grafik pendapatan bulanan/tahunan
- Owner melihat status inventory keseluruhan
- Owner melihat trend penjualan per kategori produk

**UC-O2: Access Business Reports**
- Owner melihat laporan penjualan comprehensive
- Owner melihat laporan keuangan (pendapatan, cost, profit)
- Owner melihat laporan procurement dan performance supplier
- Owner melakukan analisis perbandingan periode

**UC-O3: Monitor Performance Metrics**
- Owner melihat KPI penjualan harian/mingguan/bulanan/tahunan
- Owner melihat conversion rate dari marketplace
- Owner melihat performa cashier
- Owner melihat efficiency procurement

---

## INTERACTION MATRIX: Aktor & System Components

| Aktor | Marketplace | POS | Inventory | Procurement | Reporting |
|-------|-------------|-----|-----------|-------------|-----------|
| Customer | ✓ Full | ✗ | ✗ | ✗ | ✗ |
| Cashier | ✓ Process | ✓ Full | ✓ View | ✗ | ✗ |
| Admin | ✓ Manage Prod | ✗ | ✓ CRUD | ✗ | ✓ View/Export |
| Supervisor | ✓ Manage All | ✓ View | ✓ CRUD | ✓ Full | ✓ Full |
| Supplier | ✗ | ✗ | ✓ Provide | ✓ Provide | ✗ |
| Owner | ✓ Analytics | ✓ Analytics | ✓ Analytics | ✓ Analytics | ✓ Full |

---

## PRIMARY WORKFLOWS

### Workflow 1: Penjualan Marketplace Online
```
Customer → Browse & Add to Cart → Checkout → Payment → 
Cashier → Process Order → Complete
```

### Workflow 2: Transaksi POS Offline
```
Cashier → Create Transaction → Add Items (Check Stock) → 
Payment → Print Receipt → Completed
```

### Workflow 3: Procurement Lengkap
```
Supervisor → Create PR → Approve PR → Create PO → 
Send to Supplier → Receive Goods (GR) → Record Inventory → 
Receive Invoice → Process Payment → Completed
```

### Workflow 4: Stock Movement Analysis untuk Reorder
```
Supervisor → View Stock Movement Analysis → Identify Need → 
Create PR for Fast Moving → Approve & Create PO → 
Monitor Delivery → Complete Cycle
```

---

## SYSTEM BOUNDARIES & SCOPE

**Dalam Sistem:**
- Marketplace (Customer Interface)
- POS (Cashier Interface)
- Inventory Management (Master Data)
- Procurement Workflow (PR → PO → GR → Invoice)
- Stock Analysis & Movement Tracking
- User & Role Management
- Reporting & Analytics

**Di Luar Sistem (External):**
- Payment Gateway (External Payment Processor)
- Email Service (Notification)
- Supplier Systems (Tidak terintegrasi)
- Logistics/Delivery Service (Hanya tracked, tidak managed)

---

## KEY ENTITIES & RELATIONSHIPS

```
Customer ──→ MarketplaceOrder ──→ OrderItems
             ↓
        Cart (session-based)

User (Internal) ──→ Role (customer, admin, cashier, supervisor)

Item ──→ Category
    ──→ SupplierProduct
    ──→ Batch (dengan expiry date)

Transaction ──→ TransactionItems
            ──→ PaymentMethod
            ──→ Cart

PurchaseRequest ──→ PurchaseRequestItems ──→ SupplierProduct
                 ↓ (approval flow)
             PurchaseOrder ──→ PurchaseOrderItems
                          ──→ Supplier
                          ↓
                    GoodsReceipt ──→ GoodsReceiptItems
                               ──→ InventoryRecord
                               ↓
                          Invoice ──→ InvoiceItems
                                 ──→ Supplier
```

---

## USE CASE DIAGRAM STRUCTURE

**Actors (External):**
- Customer
- Cashier
- Admin
- Supervisor
- Supplier
- (Optional) Owner

**Main Use Cases (System):**
1. **Marketplace System**
   - Browse Products
   - Manage Cart
   - Checkout & Pay
   - View Orders

2. **POS System**
   - Create Transaction
   - Process Payment
   - Print Receipt

3. **Inventory System**
   - Manage Items
   - Manage Categories
   - Manage Suppliers
   - Track Stock Movement
   - Monitor Batches & Expiry

4. **Procurement System**
   - Create Purchase Request
   - Approve Request
   - Create Purchase Order
   - Track Goods Receipt
   - Record Inventory
   - Manage Invoices
   - Process Payments

5. **User Management**
   - Manage Users
   - Assign Roles
   - Reset Password

6. **Reporting & Analytics**
   - Sales Reports
   - Procurement Reports
   - Stock Analysis
   - Financial Reports
