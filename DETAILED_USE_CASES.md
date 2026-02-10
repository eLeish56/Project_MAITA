# DETAILED USE CASE DESCRIPTIONS
## Untuk Dokumentasi Skripsi

---

## FORMAT TEMPLATE USE CASE

```
Use Case ID: UC-XXX
Use Case Name: [Nama Use Case]
Actor(s): [Primary & Secondary Actor]
Preconditions: [Kondisi awal yang harus terpenuhi]
Main Flow: [Alur normal/happy path]
Alternative Flow: [Alur alternatif jika ada]
Postconditions: [Kondisi setelah use case selesai]
Business Rules: [Aturan bisnis yang berlaku]
```

---

## USE CASE MARKETPLACE SYSTEM

### UC-MP-01: Browse Products
**Actor(s)**: Customer, Cashier  
**Preconditions**: Customer/Cashier sudah login atau tidak login (dapat browse sebagai guest)  
**Main Flow**:
1. Customer mengakses halaman marketplace
2. Sistem menampilkan daftar produk dengan informasi dasar (nama, gambar, harga)
3. Produk ditampilkan dalam grid/list format
4. Sistem menampilkan kategori untuk filter
5. Customer dapat scroll untuk melihat lebih banyak produk
**Postconditions**: Customer dapat melihat daftar produk yang tersedia  
**Business Rules**:
- Hanya produk dengan status aktif yang ditampilkan
- Harga yang ditampilkan adalah harga regular (bukan wholesale)
- Stock status ditampilkan untuk setiap produk

---

### UC-MP-02: Search & Filter Products
**Actor(s)**: Customer  
**Preconditions**: Customer berada di halaman marketplace  
**Main Flow**:
1. Customer memasukkan kata kunci di search box atau memilih kategori
2. Sistem melakukan pencarian/filtering
3. Sistem menampilkan hasil yang relevan
4. Customer dapat melihat hasil pencarian dalam bentuk grid/list
5. Customer dapat mengubah kriteria pencarian
**Postconditions**: Produk yang sesuai dengan kriteria ditampilkan  
**Business Rules**:
- Search mencakup nama produk, kategori, deskripsi
- Filter kategori bersifat akumulatif (multi-select)
- Hasil diurutkan berdasarkan relevansi atau harga

---

### UC-MP-03: View Product Details
**Actor(s)**: Customer  
**Preconditions**: Customer telah memilih sebuah produk  
**Main Flow**:
1. Customer klik pada produk untuk melihat detail
2. Sistem menampilkan halaman detail produk dengan:
   - Gambar produk (besar)
   - Nama & deskripsi lengkap
   - Harga (regular)
   - Kategori
   - Ketersediaan stock
   - Review/rating (jika ada)
3. Customer dapat melihat tombol "Add to Cart"
**Postconditions**: Detail produk ditampilkan lengkap  
**Business Rules**:
- Harga ditampilkan dalam mata uang lokal
- Stock status real-time dari database
- Gambar default jika produk belum memiliki gambar

---

### UC-MP-04: Add to Cart
**Actor(s)**: Customer  
**Preconditions**: Customer viewing product detail atau browsing products  
**Main Flow**:
1. Customer memilih produk yang ingin dibeli
2. Customer dapat mengatur jumlah quantity (default: 1)
3. Customer klik tombol "Add to Cart"
4. Sistem memvalidasi:
   - Produk tersedia (stock > 0)
   - Quantity tidak melebihi stock
5. Sistem menambahkan item ke cart (session/database)
6. Sistem menampilkan notifikasi sukses
7. Cart badge/counter terupdate
**Postconditions**: Item ditambahkan ke cart, cart count terupdate  
**Business Rules**:
- Satu produk yang sama tidak boleh ditambahkan dua kali, tapi qty ditambahkan
- Stock validation dilakukan saat add to cart
- Cart disimpan di session untuk non-login user

---

### UC-MP-05: View Cart
**Actor(s)**: Customer  
**Preconditions**: Customer telah menambahkan minimal 1 item ke cart  
**Main Flow**:
1. Customer klik ikon cart atau go to cart page
2. Sistem menampilkan halaman cart dengan:
   - Daftar items di cart (gambar, nama, harga, qty)
   - Subtotal per item
   - Total harga
   - Tombol Continue Shopping, Update Cart, Checkout
3. Customer dapat melihat summary pesanan
**Postconditions**: Cart items ditampilkan  
**Business Rules**:
- Total dihitung otomatis dari qty x harga
- Diskon (jika ada) ditampilkan per item
- Stok ditampilkan untuk setiap item di cart

---

### UC-MP-06: Update Cart Quantity
**Actor(s)**: Customer  
**Preconditions**: Customer viewing cart dengan items  
**Main Flow**:
1. Customer mengubah quantity untuk sebuah item
2. Sistem melakukan validasi stock
3. Jika valid: quantity diupdate, total dihitung ulang
4. Jika tidak valid: sistem menampilkan error "Stok tidak cukup"
5. Total harga di-refresh otomatis
**Postconditions**: Quantity item diupdate, total dihitung ulang  
**Business Rules**:
- Quantity minimal 1
- Quantity maksimal = stock tersedia
- Subtotal item = qty x price

---

### UC-MP-07: Remove Item from Cart
**Actor(s)**: Customer  
**Preconditions**: Customer viewing cart dengan items  
**Main Flow**:
1. Customer klik tombol "Remove" atau "X" pada sebuah item
2. Sistem meminta konfirmasi (optional)
3. Item dihapus dari cart
4. Total harga dihitung ulang
5. Sistem menampilkan notifikasi bahwa item dihapus
**Postconditions**: Item dihapus dari cart, total terupdate  

---

### UC-MP-08: Clear Cart
**Actor(s)**: Customer  
**Preconditions**: Customer viewing cart dengan items  
**Main Flow**:
1. Customer klik tombol "Clear Cart" atau "Empty Cart"
2. Sistem menampilkan konfirmasi
3. Jika dikonfirmasi: semua items dihapus dari cart
4. Sistem menampilkan pesan "Cart is empty"
**Postconditions**: Semua items dihapus, cart kosong  

---

### UC-MP-09: Checkout
**Actor(s)**: Customer (harus login)  
**Preconditions**: 
- Customer sudah login
- Cart memiliki minimal 1 item
**Main Flow**:
1. Customer klik tombol "Checkout"
2. Sistem melakukan validasi login
3. Sistem menampilkan halaman checkout dengan:
   - Review items dalam cart
   - Delivery address (pilih/edit dari profile)
   - Shipping method (optional)
   - Order summary dengan total
4. Customer dapat mengubah address atau items
5. Customer klik "Continue to Payment"
**Postconditions**: Customer siap untuk payment, order detail tersimpan  
**Business Rules**:
- Customer harus login untuk checkout
- Delivery address harus valid dan lengkap
- Stock check ulang sebelum final order

---

### UC-MP-10: Process Payment
**Actor(s)**: Customer  
**Preconditions**: Customer di halaman payment/checkout  
**Main Flow**:
1. Customer memilih payment method (bank transfer, e-wallet, dll)
2. Sistem menampilkan detail pembayaran (nominal, rekening, instruksi)
3. Customer melakukan pembayaran melalui payment gateway/bank
4. Sistem menerima konfirmasi pembayaran dari gateway/bank
5. Sistem membuat order record dengan status "paid"
6. Stock dikurangi untuk setiap item
7. Sistem menampilkan order confirmation page
8. Email konfirmasi dikirim ke customer
**Postconditions**: 
- Order tersimpan dalam database dengan status paid
- Stock terupdate
- Customer menerima email konfirmasi
**Business Rules**:
- Payment timeout default 1 jam
- Order hanya created setelah payment confirmed
- Stock hanya dikurangi setelah payment sukses

---

### UC-MP-11: View Orders
**Actor(s)**: Customer  
**Preconditions**: Customer sudah login  
**Main Flow**:
1. Customer mengakses menu "My Orders" atau "Order History"
2. Sistem menampilkan daftar semua order customer dengan:
   - Order ID/Code
   - Order date
   - Items count
   - Total amount
   - Order status
3. Customer dapat filter berdasarkan status atau date range
4. Customer dapat melihat order tertentu dengan klik detail
**Postconditions**: Daftar orders ditampilkan  
**Business Rules**:
- Hanya orders milik customer yang ditampilkan
- Diurutkan dari tanggal terbaru ke terlama

---

### UC-MP-12: Track Order Status
**Actor(s)**: Customer  
**Preconditions**: Customer viewing order list atau detail  
**Main Flow**:
1. Customer dapat melihat status order dari list (pending, processing, completed, cancelled)
2. Customer klik pada order untuk melihat timeline/history status
3. Sistem menampilkan:
   - Current status
   - Status history dengan timestamp
   - Estimasi delivery date (jika ada)
4. Customer dapat melihat rincian processing
**Postconditions**: Order status dan history ditampilkan  
**Business Rules**:
- Status di-update oleh sistem dan cashier
- Customer dapat melihat real-time status updates

---

### UC-MP-13: View Order Details
**Actor(s)**: Customer  
**Preconditions**: Customer memilih sebuah order  
**Main Flow**:
1. Customer klik order untuk melihat detail lengkap
2. Sistem menampilkan:
   - Order ID/Code
   - Order date dan delivery date
   - Billing & delivery address
   - Items list dengan price breakdown
   - Payment method used
   - Total amount (itemized)
   - Order status & history
   - Invoice (downloadable)
**Postconditions**: Detail order ditampilkan lengkap  

---

## USE CASE POS SYSTEM

### UC-POS-01: Create Transaction
**Actor(s)**: Cashier  
**Preconditions**: Cashier sudah login, POS system initialized  
**Main Flow**:
1. Cashier klik tombol "New Transaction"
2. Sistem membuat transaksi baru dengan:
   - Unique invoice number (auto-generated)
   - Current timestamp
   - Cashier ID
3. System initialize empty cart untuk transaksi
4. Sistem siap untuk menambah items
**Postconditions**: 
- Transaksi baru dibuat dengan ID/invoice number
- Cart kosong dan siap untuk items
**Business Rules**:
- Invoice number format: YYYYMMDD-XXXXX (sequential)
- Setiap transaksi linked dengan cashier untuk audit

---

### UC-POS-02: Add Item to Cart (POS)
**Actor(s)**: Cashier  
**Preconditions**: Transaksi sudah di-create, cart kosong  
**Main Flow**:
1. Cashier scan barcode ATAU input item code
2. Sistem validate item code
3. Jika valid: sistem check stock
4. Jika stock available:
   - Item ditambah ke cart dengan qty default 1
   - Item details ditampilkan (name, price, stock)
   - Subtotal dihitung
5. Jika stock tidak tersedia: error message ditampilkan
6. Cashier dapat continue menambah item
**Postconditions**: Item ditambahkan ke cart, display terupdate  
**Business Rules**:
- Barcode atau item code harus exact match
- Stock validation real-time dari database
- Item tidak boleh duplicate di cart (qty ditambah saja)

---

### UC-POS-03: Check Stock (POS)
**Actor(s)**: Cashier  
**Preconditions**: Cashier entering item code  
**Main Flow**:
1. Sistem query database untuk stock item
2. Sistem menampilkan stock level
3. Sistem membandingkan dengan qty yang diinput
4. Jika qty > stock: reject dan tampilkan error
5. Jika qty <= stock: accept dan lanjut ke cart
**Postconditions**: Stock status divalidasi  
**Business Rules**:
- Stock validation dilakukan setiap add item
- Stock dilihat real-time, bukan cache
- Sistem dapat menampilkan alert jika stock menipis

---

### UC-POS-04: Update Item Quantity (POS)
**Actor(s)**: Cashier  
**Preconditions**: Item sudah di cart  
**Main Flow**:
1. Cashier dapat klik item di cart dan ubah quantity
2. Sistem validate stock untuk qty baru
3. Jika valid: qty diupdate, subtotal dihitung ulang
4. Jika tidak valid: error message ditampilkan
5. Total transaksi di-refresh
**Postconditions**: Quantity item diupdate, total recalculated  

---

### UC-POS-05: Remove Item from Cart (POS)
**Actor(s)**: Cashier  
**Preconditions**: Item ada di cart  
**Main Flow**:
1. Cashier klik tombol "Remove" pada item
2. Item dihapus dari cart
3. Total transaksi dihitung ulang
**Postconditions**: Item dihapus, total terupdate  

---

### UC-POS-06: Complete Transaction
**Actor(s)**: Cashier  
**Preconditions**: Cart memiliki items  
**Main Flow**:
1. Cashier klik tombol "Proceed to Payment" atau "Complete Sale"
2. Sistem menampilkan transaction summary:
   - Items list dengan harga
   - Subtotal
   - Tax (jika applicable)
   - Total amount
3. Cashier select payment method
4. Sistem process payment (verify/confirm)
5. Jika payment success:
   - Transaksi disimpan ke database
   - Stock dikurangi untuk setiap item
   - Transaction record dibuat
6. Jika payment failed: error message ditampilkan
**Postconditions**: 
- Transaksi selesai dan disimpan
- Stock terupdate
- Ready untuk transaksi baru
**Business Rules**:
- Stock hanya dikurangi jika payment success
- Transaksi tidak bisa di-edit setelah selesai
- Receipt dapat di-print atau di-email

---

### UC-POS-07: Select Payment Method (POS)
**Actor(s)**: Cashier  
**Preconditions**: Transaction ready untuk payment  
**Main Flow**:
1. Cashier dapat memilih dari payment methods yang tersedia:
   - Cash
   - Debit/Credit Card
   - Digital Wallet
   - Bank Transfer
   - Check
   - Others (configurable)
2. Sistem tampilkan pilihan
3. Cashier select method
4. Sistem prepare untuk payment processing
**Postconditions**: Payment method selected  
**Business Rules**:
- Payment methods configurable oleh supervisor
- Setiap method dapat memiliki processing rule berbeda

---

### UC-POS-08: Print Receipt
**Actor(s)**: Cashier  
**Preconditions**: Transaksi selesai  
**Main Flow**:
1. Sistem automatic membuka print preview
2. Cashier dapat klik "Print" atau "Close"
3. Receipt di-print ke printer POS
4. Receipt menampilkan:
   - Store name & info
   - Invoice number & date/time
   - Items dengan harga
   - Subtotal, tax, total
   - Payment method
   - Cashier name
   - Thank you message
**Postconditions**: Receipt printed  
**Business Rules**:
- Receipt auto-print (optional setting)
- Reprint capability available
- Digital receipt dapat di-email/SMS

---

### UC-POS-09: View Transactions (POS)
**Actor(s)**: Cashier  
**Preconditions**: Cashier logged in  
**Main Flow**:
1. Cashier dapat access "Transaction History" menu
2. Sistem menampilkan list semua transaksi yang diproses:
   - Invoice number
   - Date/Time
   - Total amount
   - Payment method
3. Cashier dapat filter by date range
4. Cashier dapat search by invoice number
**Postconditions**: Transaction list ditampilkan  

---

### UC-POS-10: View Transaction Details (POS)
**Actor(s)**: Cashier  
**Preconditions**: Cashier selecting transaction dari history  
**Main Flow**:
1. Cashier klik transaction untuk melihat detail
2. Sistem menampilkan:
   - Transaction header (invoice, date, cashier)
   - Items dengan qty & price
   - Subtotal, tax, total
   - Payment method & amount
   - Receipt preview
3. Cashier dapat print/reprint receipt
**Postconditions**: Transaction detail ditampilkan  

---

## USE CASE PROCUREMENT SYSTEM

### UC-PROC-01: Create Purchase Request (PR)
**Actor(s)**: Supervisor, Admin (dengan restrict)  
**Preconditions**: Stock analysis completed, kebutuhan barang diidentifikasi  
**Main Flow**:
1. Supervisor membuka menu "Procurement" → "Purchase Requests"
2. Supervisor klik tombol "Create New PR"
3. Sistem menampilkan form PR dengan fields:
   - PR Date (auto: current date)
   - Supplier (dropdown)
   - Required date (estimated)
   - Items needed (multiple entries):
     - Item selection
     - Quantity required
     - Unit price (optional, reference only)
   - Notes/Remarks
4. Supervisor fill form dengan data
5. Supervisor select items yang akan dipesan
6. Supervisor specify quantity untuk setiap item
7. Supervisor submit PR
8. Sistem validate data dan create PR record
9. PR auto status: "Draft" → "Submitted"
10. Notification sent ke supervisor untuk approval
**Postconditions**: 
- PR record dibuat dengan status "Submitted"
- PR ID/number generated
- Waiting untuk approval
**Business Rules**:
- PR hanya dapat dibuat oleh supervisor atau admin dengan approval
- Supplier harus valid (existing di master data)
- Quantity harus lebih dari 0
- Item harus tersedia di supplier catalog

---

### UC-PROC-02: Approve Purchase Request
**Actor(s)**: Supervisor  
**Preconditions**: PR sudah submitted, waiting approval  
**Main Flow**:
1. Supervisor melihat list PR yang pending approval
2. Supervisor klik PR untuk review
3. Supervisor melihat detail PR:
   - Supplier info
   - Items dengan quantity
   - Estimated cost (calculated)
   - Creation date & creator
4. Supervisor dapat:
   - Review items dan quantity
   - Check supplier history
   - Add comments
5. Supervisor klik "Approve"
6. Sistem update PR status menjadi "Approved"
7. Notification sent bahwa PR approved (ready untuk PO creation)
**Postconditions**: PR approved, status updated, ready untuk PO  
**Business Rules**:
- Hanya supervisor dapat approve PR
- PR detail tidak bisa diubah setelah approve
- Reject option tersedia dengan reason

---

### UC-PROC-03: Reject Purchase Request
**Actor(s)**: Supervisor  
**Preconditions**: PR submitted, supervisor reviewing  
**Main Flow**:
1. Supervisor review PR dan menemukan masalah
2. Supervisor klik "Reject"
3. Sistem menampilkan form untuk input reason
4. Supervisor input reason/notes
5. Supervisor submit reject
6. Sistem update PR status menjadi "Rejected"
7. Notification sent ke creator dengan reason
**Postconditions**: PR rejected, PR tidak dapat dilanjutkan ke PO  

---

### UC-PROC-04: Create Purchase Order (PO)
**Actor(s)**: Supervisor  
**Preconditions**: PR approved  
**Main Flow**:
1. Supervisor buka PR yang approved
2. Supervisor klik "Create PO" atau "Convert to PO"
3. Sistem auto-populate PO dengan data dari PR:
   - Supplier
   - Items & quantity
   - Reference PR number
4. Supervisor dapat edit:
   - Unit price untuk setiap item (dari supplier catalog atau manual)
   - Delivery date
   - Payment terms
   - Notes
5. Supervisor review total cost (calculated)
6. Supervisor submit PO
7. Sistem create PO record dengan status "Draft"
8. PO ID/number generated
**Postconditions**: 
- PO created dengan status "Draft"
- PO linked dengan PR
- Ready untuk sending to supplier
**Business Rules**:
- PO hanya bisa dibuat dari approved PR
- Unit price bisa reference dari supplier catalog atau manual
- Total PO = sum(qty x unit price) + tax + shipping

---

### UC-PROC-05: Edit PO Prices
**Actor(s)**: Supervisor  
**Preconditions**: PO in draft status  
**Main Flow**:
1. Supervisor open PO yang masih draft
2. Supervisor klik "Edit Prices"
3. Sistem menampilkan form dengan items & current prices
4. Supervisor dapat modify:
   - Unit price per item
   - Discount (item level atau PO level)
   - Tax rate
   - Shipping cost
5. Sistem auto-calculate total
6. Supervisor review updated total
7. Supervisor save changes
**Postconditions**: PO prices updated  
**Business Rules**:
- PO price hanya bisa di-edit saat draft
- Setelah sent to supplier, hanya confirm prices allowed

---

### UC-PROC-06: Confirm PO Prices
**Actor(s)**: Supervisor  
**Preconditions**: PO in draft status, prices edited  
**Main Flow**:
1. Supervisor review final prices di PO
2. Supervisor klik "Confirm Prices"
3. Sistem lock PO prices (tidak bisa di-edit lagi)
4. PO status update ke "Ready to Send"
**Postconditions**: Prices confirmed & locked  

---

### UC-PROC-07: Mark PO as Sent
**Actor(s)**: Supervisor  
**Preconditions**: PO confirmed, ready untuk dikirim  
**Main Flow**:
1. Supervisor print/generate PO document (PDF)
2. Supervisor kirim PO ke supplier (email, fisik, atau system integration)
3. Supervisor klik "Mark as Sent" di sistem
4. Sistem menampilkan form:
   - Send date
   - Send method (email, courier, etc)
   - Confirmation number (optional)
5. Supervisor submit
6. PO status update menjadi "Sent"
7. Notification sent ke supervisor
**Postconditions**: 
- PO marked as sent
- Status updated
- Waiting untuk goods receipt
**Business Rules**:
- PO hanya bisa marked sent jika confirmed
- Send date recorded untuk tracking

---

### UC-PROC-08: Create Goods Receipt (GR)
**Actor(s)**: Supervisor  
**Preconditions**: 
- PO sent
- Goods tiba dari supplier
**Main Flow**:
1. Supervisor menerima barang dan dokumentasi dari supplier
2. Supervisor buka menu "Goods Receipt"
3. Supervisor klik "Create GR" atau select related PO
4. Sistem menampilkan form dengan:
   - PO reference
   - Supplier info
   - Expected items (dari PO)
   - Receiving date
5. Supervisor input received items:
   - Item selection (auto-filled dari PO)
   - Quantity received
   - Condition (good/damaged/etc)
   - Lot/Batch number
   - Expiry date (untuk produk dengan expiry)
6. Supervisor dapat add notes
7. Supervisor submit GR
8. Sistem create GR record dengan status "Received"
9. Stock not yet updated (pending inventory record)
**Postconditions**: 
- GR created & linked dengan PO
- Items recorded
- Waiting untuk inventory record creation
**Business Rules**:
- Quantity received bisa berbeda dengan PO quantity (over/under delivery)
- Lot number & expiry date required untuk tracking
- GR dapat di-print untuk dokumentasi

---

### UC-PROC-09: Create Inventory Record
**Actor(s)**: Supervisor  
**Preconditions**: GR created & items verified  
**Main Flow**:
1. Supervisor verify barang dari GR (check physical stock dengan GR)
2. Supervisor buka GR record
3. Supervisor klik "Create Inventory Record"
4. Sistem menampilkan items dari GR
5. Supervisor confirm items:
   - Verify quantity received
   - Check item condition
   - Verify batch/lot number
6. Supervisor dapat adjust quantity jika ada discrepancy
7. Supervisor submit inventory record
8. Sistem:
   - Create inventory record
   - Update master item stock (increase)
   - Create batch record untuk item (jika batch)
   - Update stock movement log
9. GR status update menjadi "Recorded"
**Postconditions**: 
- Inventory record created
- Stock updated in system
- Batch/lot tracked
**Business Rules**:
- Stock update only happens setelah inventory record
- Batch tracking mandatory untuk expiry items
- Stock movement log created untuk audit trail

---

### UC-PROC-10: Create Invoice
**Actor(s)**: Supervisor  
**Preconditions**: 
- GR created (goods received)
- Supervisor received invoice dari supplier
**Main Flow**:
1. Supervisor menerima invoice dari supplier
2. Supervisor buka menu "Invoices"
3. Supervisor klik "Create Invoice"
4. Sistem menampilkan form dengan options:
   - Link ke existing PO (untuk auto-populate)
   - Manual entry
5. Supervisor select PO atau input invoice details:
   - Supplier
   - Invoice number
   - Invoice date
   - Items & amounts
   - Tax
   - Shipping cost
   - Total invoice amount
   - Payment terms
   - Due date
6. Supervisor dapat upload invoice file (PDF/image)
7. Supervisor submit invoice
8. Sistem create invoice record dengan status "Pending Payment"
**Postconditions**: 
- Invoice record created
- Linked dengan PO & GR
- Waiting untuk payment
**Business Rules**:
- Invoice amount harus match dengan PO (atau supervisor dapat override dengan justification)
- Invoice file upload optional untuk documentation
- Invoice dapat di-print

---

### UC-PROC-11: Mark Invoice as Paid
**Actor(s)**: Supervisor  
**Preconditions**: 
- Invoice created
- Payment made to supplier
**Main Flow**:
1. Supervisor verify payment terbuat ke supplier
2. Supervisor buka invoice record
3. Supervisor klik "Mark as Paid"
4. Sistem menampilkan form:
   - Payment date
   - Payment method
   - Payment reference (bank transfer ID, check number, etc)
   - Amount paid
5. Supervisor dapat upload payment proof (bank statement, receipt, etc)
6. Supervisor submit
7. Sistem update invoice status menjadi "Paid"
8. Notification sent bahwa invoice sudah paid
**Postconditions**: 
- Invoice marked as paid
- Status updated
- Payment record created
- Procurement cycle complete
**Business Rules**:
- Payment amount harus match invoice amount (atau admin override)
- Payment proof optional untuk documentation
- Paid invoice cannot be modified

---

### UC-PROC-12: View Procurement Dashboard
**Actor(s)**: Supervisor  
**Preconditions**: Supervisor logged in  
**Main Flow**:
1. Supervisor access "Procurement Dashboard"
2. Sistem menampilkan overview:
   - PR statistics (pending, approved, rejected)
   - PO statistics (draft, sent, received)
   - GR statistics (pending, recorded)
   - Invoice statistics (pending, paid)
   - Recent transactions
   - Key metrics (total spend, avg delivery time, etc)
3. Supervisor dapat drill-down ke detail untuk setiap section
4. Supervisor dapat view pending approvals
**Postconditions**: Dashboard displayed dengan real-time data  

---

### UC-PROC-13: View Purchase Reports
**Actor(s)**: Supervisor, Admin  
**Preconditions**: Supervisor/Admin logged in  
**Main Flow**:
1. User access "Purchase Reports"
2. User select report type:
   - PR by period
   - PO by supplier
   - Goods receipt history
   - Invoice payment status
   - Supplier performance
3. User specify filter:
   - Date range
   - Supplier
   - Status
4. Sistem generate report dengan details:
   - Document count
   - Total amount
   - Status breakdown
   - List dengan details
5. User dapat export to Excel/PDF
**Postconditions**: Report displayed & can be exported  

---

## USE CASE USER MANAGEMENT

### UC-USER-01: Manage Users (CRUD)
**Actor(s)**: Supervisor  
**Preconditions**: Supervisor logged in  
**Main Flow**:

#### CREATE User:
1. Supervisor klik "Create New User"
2. Sistem menampilkan form dengan fields:
   - Name
   - Username (unique)
   - Email
   - Phone
   - Address
   - Password (temporary)
   - Role (admin, cashier, supervisor)
   - Position
   - Active status
3. Supervisor fill form
4. Supervisor submit
5. Sistem create user record
6. Email sent ke user dengan temp password

#### READ User:
1. Supervisor klik "View Users"
2. Sistem menampilkan list semua users
3. Supervisor dapat filter by role atau search by name

#### UPDATE User:
1. Supervisor klik user untuk edit
2. Sistem menampilkan form dengan current data
3. Supervisor dapat modify fields (kecuali ID)
4. Supervisor submit changes
5. Sistem update user record

#### DELETE User:
1. Supervisor klik delete button
2. Sistem menampilkan confirmation
3. Jika confirmed: user record deleted (atau soft delete)
4. User tidak bisa login lagi

**Postconditions**: User records managed per required action  
**Business Rules**:
- Username & email must be unique
- Password harus minimal 8 karakter
- Role assignment controls access level
- Deleted users cannot be restored (atau dengan admin action)

---

### UC-USER-02: Assign Roles
**Actor(s)**: Supervisor  
**Preconditions**: User record exists  
**Main Flow**:
1. Supervisor edit user record
2. Supervisor change role dropdown
3. Sistem validasi role change
4. Supervisor submit
5. Sistem update user role
6. New permissions immediately effective
**Postconditions**: User role changed, permissions updated  
**Business Rules**:
- Role change dapat dilakukan kapan saja
- Supervisor tidak bisa hapus diri sendiri
- Harus minimum 1 supervisor dalam sistem

---

### UC-USER-03: Reset Password
**Actor(s)**: Supervisor  
**Preconditions**: User record exists  
**Main Flow**:
1. Supervisor select user
2. Supervisor klik "Reset Password"
3. Sistem generate temporary password
4. Temporary password sent ke user email
5. User akan diminta change password pada login berikutnya
**Postconditions**: User password reset  
**Business Rules**:
- Temp password valid untuk 1 login saja
- User must change password
- Password tidak stored in plain text

---

## USE CASE REPORTING & ANALYTICS

### UC-REP-01: View Sales Reports
**Actor(s)**: Supervisor, Admin, Owner  
**Preconditions**: User logged in dengan access reports  
**Main Flow**:
1. User access "Sales Reports"
2. User select report type:
   - Daily sales
   - Monthly sales
   - Sales by category
   - Sales by payment method
   - Sales by cashier
3. User specify filters:
   - Date range
   - Category (optional)
   - Payment method (optional)
   - Cashier (optional)
4. Sistem generate report dengan:
   - Transaction count
   - Total sales amount
   - Breakdown by category/method/cashier
   - Average transaction value
   - Charts/graphs
5. User dapat export to Excel/PDF
6. User dapat print report
**Postconditions**: Sales report displayed & can be exported  

---

### UC-REP-02: Stock Movement Analysis
**Actor(s)**: Supervisor  
**Preconditions**: Supervisor logged in  
**Main Flow**:
1. Supervisor access "Stock Movement Analysis"
2. Sistem menampilkan dashboard dengan:
   - Fast-moving items (high velocity)
   - Slow-moving items (low velocity)
   - Stock status warnings
   - Movement charts
3. Supervisor dapat:
   - Filter by category
   - View detailed history per item
   - Export data
4. Supervisor menggunakan insight untuk:
   - Reorder decision
   - Pricing strategy
   - Clearance actions
**Postconditions**: Stock analysis displayed dengan actionable insights  

---

Dokumen ini dapat diperluas dengan use cases tambahan sesuai kebutuhan dokumentasi skripsi. Setiap use case dapat dikembangkan lebih detail dengan exception handling dan alternative flows yang lebih comprehensive.
