# Daftar Aktor dan Fitur Sistem POS Terintegrasi

Dokumen ini mencakup semua aktor (user roles) dalam sistem dan fitur-fitur lengkapnya untuk pembuatan narasi usecase diagram.

---

## 1. AKTOR: CUSTOMER (Pelanggan)

### Deskripsi:
Pengguna publik yang berinteraksi dengan sistem melalui marketplace online. Customer dapat melakukan registrasi mandiri, berbelanja produk, dan mengelola pesanan mereka.

### Fitur-Fitur:

#### 1.1 Authentication & Account
- **Register** - Mendaftar akun customer baru
- **Login** - Masuk ke sistem dengan email/username
- **Logout** - Keluar dari sistem
- **View Profile** - Melihat profil pribadi
- **Edit Profile** - Mengubah data profil (nama, email, phone, address)

#### 1.2 Marketplace Shopping
- **View Marketplace Dashboard** - Melihat halaman utama marketplace dengan daftar produk
- **Browse Products** - Melihat catalog produk lengkap
- **View Product Details** - Melihat informasi detail produk (harga, deskripsi, gambar, stok)
- **Search Products** - Mencari produk berdasarkan kategori atau kata kunci

#### 1.3 Shopping Cart (Marketplace)
- **Add to Cart** - Menambahkan produk ke keranjang
- **View Cart** - Melihat daftar item di keranjang
- **Update Cart Quantity** - Mengubah jumlah item di keranjang
- **Remove Item from Cart** - Menghapus item dari keranjang
- **Clear Cart** - Mengosongkan seluruh keranjang

#### 1.4 Checkout & Payment
- **Proceed to Checkout** - Lanjut ke tahap checkout
- **Confirm Order** - Mengkonfirmasi pesanan dengan detail pengiriman
- **Select Payment Method** - Memilih metode pembayaran online
- **Process Payment** - Melakukan pembayaran untuk pesanan

#### 1.5 Order Management
- **View Orders** - Melihat daftar pesanan yang pernah dibuat
- **View Order Details** - Melihat detail pesanan (items, status, harga)
- **Track Order Status** - Melacak status pesanan (pending, processing, completed)

---

## 2. AKTOR: CASHIER (Kasir/POS Staff)

### Deskripsi:
Staf toko yang menangani transaksi penjualan di counter, memproses pesanan online/marketplace, dan manajemen cart internal.

### Fitur-Fitur:

#### 2.1 POS Transaction Management
- **Create New Transaction** - Membuat transaksi penjualan baru
- **Add Item to Cart** - Menambahkan item ke cart POS
- **Update Item Quantity** - Mengubah jumlah item di cart
- **Remove Item from Cart** - Menghapus item dari cart
- **Clear All Cart** - Mengosongkan cart
- **Check Stock** - Mengecek ketersediaan stok item real-time
- **View Item Details** - Melihat informasi item dari database inventory
- **Get Invoice** - Mengambil nomor invoice untuk transaksi

#### 2.2 Transaction Processing
- **Complete Transaction** - Menyelesaikan dan menyimpan transaksi
- **Select Payment Method** - Memilih metode pembayaran
- **Print Receipt** - Mencetak struk transaksi
- **View Transaction List** - Melihat daftar transaksi yang telah diproses
- **View Transaction Details** - Melihat detail transaksi tertentu

#### 2.3 Online Order Processing
- **View Online Orders** - Melihat daftar pesanan online yang belum diproses
- **View Order Items** - Melihat detail item dalam pesanan online
- **Process Online Payment** - Memproses pembayaran pesanan online (status: debt → paid)

#### 2.4 Marketplace Order Processing
- **View Marketplace Orders** - Melihat daftar pesanan marketplace yang belum diproses
- **View Marketplace Order Items** - Melihat detail item dalam pesanan marketplace
- **Process Marketplace Order Payment** - Memproses pembayaran pesanan marketplace

#### 2.5 Cart Management (POS)
- **Initialize Cart** - Membuat cart baru untuk transaksi
- **Manage Cart Items** - Kelola item di dalam cart
- **Clear Cart** - Kosongkan cart

---

## 3. AKTOR: ADMIN

### Deskripsi:
Administrator sistem yang memiliki akses penuh untuk manajemen data master, customer, dan user (meski user management disentralkan ke supervisor).

### Fitur-Fitur:

#### 3.1 Customer Management
- **View Customer List** - Melihat daftar semua customer
- **Create New Customer** - Membuat customer baru
- **Edit Customer Data** - Mengubah data customer
- **Delete Customer** - Menghapus customer dari sistem

#### 3.2 Product Management (Inventory)
- **View Item List** - Melihat daftar semua item/produk
- **Create New Item** - Menambahkan item baru ke inventory
- **Edit Item Details** - Mengubah informasi item (nama, harga, deskripsi, gambar)
- **Set Item Price** - Mengatur harga regular dan wholesale
- **Upload Item Image** - Upload gambar produk
- **Delete Item** - Menghapus item dari sistem

#### 3.3 Category Management
- **View Category List** - Melihat daftar kategori produk
- **Create New Category** - Membuat kategori baru
- **Edit Category** - Mengubah informasi kategori
- **Delete Category** - Menghapus kategori

#### 3.4 Supplier Management
- **View Supplier List** - Melihat daftar supplier
- **Create New Supplier** - Menambahkan supplier baru
- **Edit Supplier Data** - Mengubah data supplier
- **Delete Supplier** - Menghapus supplier

#### 3.5 Transaction Reports
- **View Transaction Report** - Melihat laporan transaksi
- **Filter Transactions** - Filter laporan berdasarkan tanggal, metode pembayaran, dll
- **View Transaction Details** - Melihat detail transaksi tertentu
- **Export Sales Report** - Export laporan penjualan ke file (Excel/PDF)

---

## 4. AKTOR: SUPERVISOR

### Deskripsi:
Supervisor memiliki kontrol penuh atas operasional POS, manajemen user internal, dan persetujuan dalam workflow procurement.

### Fitur-Fitur:

#### 4.1 User Management (Internal Staff)
- **View User List** - Melihat daftar semua user internal (admin, cashier, supervisor)
- **Create New User** - Membuat user baru dengan role tertentu
- **Edit User Details** - Mengubah informasi user
- **Deactivate/Activate User** - Mengaktifkan atau menonaktifkan user
- **Delete User** - Menghapus user dari sistem
- **Reset User Password** - Reset password user

#### 4.2 Inventory Management (Master Data)
- **Manage Items** - CRUD lengkap untuk item
- **Manage Categories** - CRUD lengkap untuk kategori
- **Manage Suppliers** - CRUD lengkap untuk supplier
- **Manage Supplier Products** - Mengelola produk dari setiap supplier

#### 4.3 Batch Management (Expiry Tracking)
- **View Batch List** - Melihat daftar batch produk
- **View Item Batches** - Melihat batch untuk item tertentu
- **Update Batch Expiry Status** - Update status kadaluarsa batch
- **Monitor Expiring Items** - Monitor item yang akan kadaluarsa

#### 4.4 Stock Movement Analysis
- **View Stock Movement Dashboard** - Dashboard analisis pergerakan stok
- **View Fast-Moving Items** - Lihat produk dengan penjualan cepat
- **View Slow-Moving Items** - Lihat produk dengan penjualan lambat
- **Analyze Stock Movement** - Analisis detail pergerakan stok
- **Configure Movement Settings** - Konfigurasi parameter perhitungan
- **Export Stock Analysis** - Export hasil analisis stok

#### 4.5 Procurement Workflow Management

##### 4.5.1 Purchase Request (PR)
- **View PR List** - Melihat daftar semua Purchase Request
- **Create PR** - Membuat Purchase Request baru
- **View PR Details** - Melihat detail PR
- **Approve PR** - Menyetujui Purchase Request
- **Reject PR** - Menolak Purchase Request
- **Generate PR PDF** - Menghasilkan dokumen PDF untuk PR

##### 4.5.2 Purchase Order (PO)
- **View PO List** - Melihat daftar Purchase Order
- **View PO Details** - Melihat detail PO dengan item dan supplier
- **Create Direct PO** - Membuat PO langsung dari PR yang disetujui
- **Mark PO as Sent** - Menandai PO sudah dikirim ke supplier
- **Generate PO PDF** - Menghasilkan dokumen PDF untuk PO
- **Edit PO Prices** - Edit harga pada PO sebelum dikirim
- **Confirm PO Prices** - Konfirmasi harga PO setelah review

##### 4.5.3 Goods Receipt (GR)
- **View Goods Receipt List** - Melihat daftar dokumen Goods Receipt
- **Create Goods Receipt** - Membuat GR dari PO yang diterima
- **Record Items in GR** - Mencatat item yang diterima dari supplier
- **View GR Details** - Melihat detail GR dengan item yang diterima
- **Download GR Document** - Download dokumen GR (PDF)

##### 4.5.4 Inventory Record (Recording Fisik)
- **Create Inventory Record** - Membuat catatan inventory dari GR
- **Record Received Items** - Mencatat item yang diterima ke sistem
- **View Inventory Records** - Melihat daftar inventory records
- **View Record Details** - Melihat detail record tertentu

##### 4.5.5 Invoice Management
- **Create Invoice** - Membuat invoice dari PO
- **View Invoice List** - Melihat daftar invoice dari supplier
- **View Invoice Details** - Melihat detail invoice
- **Mark Invoice as Paid** - Menandai invoice sudah dibayar
- **Upload Invoice File** - Upload file invoice dari supplier
- **Upload Payment Proof** - Upload bukti pembayaran
- **Download Invoice** - Download file invoice
- **Download Payment Proof** - Download bukti pembayaran

#### 4.6 Procurement Dashboard & Reporting
- **View Procurement Dashboard** - Dashboard overview workflow procurement
- **View Purchase Reports** - Laporan pembelian dari supplier
- **View Report Details** - Detail laporan pembelian tertentu
- **Export Purchase Reports** - Export laporan pembelian ke file

#### 4.7 Payment Method Management
- **View Payment Methods** - Melihat daftar metode pembayaran
- **Create New Payment Method** - Menambahkan metode pembayaran baru
- **Edit Payment Method** - Mengubah konfigurasi metode pembayaran
- **Delete Payment Method** - Menghapus metode pembayaran

#### 4.8 Absence Management (Cuti/Izin)
- **View Absence Records** - Melihat daftar cuti/izin karyawan
- **Create Absence Record** - Membuat catatan cuti/izin baru
- **Edit Absence Record** - Mengubah catatan cuti/izin
- **Delete Absence Record** - Menghapus catatan cuti/izin

#### 4.9 Transaction & General Reports
- **View Transaction Reports** - Laporan lengkap transaksi penjualan
- **Filter & Search Reports** - Filter laporan dengan berbagai kriteria
- **Export Transaction Reports** - Export laporan penjualan
- **View Sales Trends** - Melihat tren penjualan

#### 4.10 Profile Management
- **View Own Profile** - Melihat profil pribadi
- **Edit Own Profile** - Mengubah data profil pribadi

---

## 5. AKTOR: SUPPLIER

### Deskripsi:
Pihak eksternal yang menyediakan produk/barang untuk sistem. Supplier terdaftar dalam master data dan memiliki produk yang dapat dipesan.

### Fitur-Fitur (Dalam Konteks Sistem):

#### 5.1 Product Catalog
- **Provide Product List** - Daftar produk yang ditawarkan tersimpan dalam sistem
- **Maintain Product Information** - Data produk supplier (harga, spesifikasi) tersimpan

#### 5.2 Purchase Order Processing
- **Receive Purchase Order** - Supplier menerima PO dari sistem
- **Provide Goods** - Mengirim barang sesuai PO
- **Deliver with Documentation** - Mengirim barang dengan dokumen packing

#### 5.3 Invoicing
- **Create Invoice** - Supplier membuat invoice untuk pengiriman
- **Submit Invoice** - Supplier mengirimkan invoice ke sistem
- **Receive Payment** - Supplier menerima pembayaran sesuai invoice

---

## 6. AKTOR: OWNER/PEMILIK

### Deskripsi:
Pemilik bisnis yang memiliki akses ke dashboard dan laporan kinerja bisnis keseluruhan.

### Fitur-Fitur (Opsional/Potensial):

#### 6.1 Dashboard & Overview
- **View Main Dashboard** - Dashboard KPI dan ringkasan bisnis
- **View Sales Analytics** - Analisis penjualan harian/bulanan/tahunan
- **View Monthly Income Chart** - Grafik pendapatan bulanan
- **View Inventory Status** - Status inventory keseluruhan

#### 6.2 Reports & Analytics
- **View Comprehensive Reports** - Laporan lengkap operasional
- **Access Financial Reports** - Laporan keuangan
- **View Procurement Reports** - Laporan pembelian dan supplier
- **Track KPIs** - Melacak key performance indicators

---

## RINGKASAN FITUR PER ROLE

### Customer: 16 Fitur
Fokus: Berbelanja di marketplace, manajemen pesanan online

### Cashier: 19 Fitur  
Fokus: Transaksi POS, pemrosesan pesanan online/marketplace

### Admin: 14 Fitur
Fokus: Master data (item, kategori, supplier, customer)

### Supervisor: 60+ Fitur
Fokus: Manajemen lengkap (user, procurement, inventory, laporan, analisis)

### Supplier: 5 Fitur
Fokus: Interaksi dalam workflow procurement (eksternal)

### Owner: 4 Fitur (Potensial)
Fokus: Dashboard dan laporan strategis

---

## WORKFLOW PROCUREMENT (Detail Untuk Usecase Diagram)

```
Workflow Tahapan Procurement:
1. Admin/Supervisor membuat Purchase Request (PR)
2. Supervisor mengsetujui atau menolak PR
3. Dari PR yang disetujui, dibuat Purchase Order (PO)
4. PO dikirim ke Supplier
5. Supplier mengirim barang
6. Admin/Supervisor membuat Goods Receipt (GR) saat barang tiba
7. Admin/Supervisor membuat Inventory Record untuk mencatat barang ke stok
8. Supplier mengirim Invoice
9. Admin/Supervisor membuat Invoice record di sistem
10. Invoice ditandai sebagai paid setelah pembayaran
```

---

## WORKFLOW POS & MARKETPLACE

```
Workflow Penjualan:

A. POS (Offline/Counter):
   1. Cashier membuat transaksi baru
   2. Cashier menambahkan item ke cart (cek stok real-time)
   3. Cashier update qty atau hapus item
   4. Cashier selesaikan transaksi dengan pilih payment method
   5. Sistem generate nomor invoice
   6. Cashier cetak receipt

B. Marketplace (Online):
   1. Customer browse produk di marketplace
   2. Customer tambah item ke keranjang
   3. Customer lihat cart dan ubah qty
   4. Customer checkout
   5. Customer pilih payment method dan bayar
   6. Pesanan masuk ke sistem dengan status "pending"
   7. Cashier lihat pesanan online di menu "Online Orders"
   8. Cashier proses pembayaran (ubah status menjadi paid)
```

---

## NOTES UNTUK USECASE DIAGRAM

1. **Primary Actors**: Customer, Cashier, Admin, Supervisor
2. **Secondary Actors**: Supplier, System (untuk generate invoice, laporan)
3. **System Boundary**: Marketplace + POS + Procurement + Inventory
4. **Key Use Cases**:
   - Browse & Purchase Products
   - Process POS Transaction
   - Create Purchase Request
   - Approve/Reject PR
   - Create & Process Purchase Order
   - Receive Goods & Create GR
   - Manage Invoice & Payment
   - Generate Reports
   - Manage Inventory
   - Track Stock Movement
