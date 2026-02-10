# Template Excel untuk Testing Checklist

## Cara Menggunakan Excel Template

Jika Anda lebih suka menggunakan Excel, ikuti langkah-langkah di bawah:

### Setup Awal:

1. **Buat File Excel Baru** dengan struktur berikut:

| Test ID | Fitur | Deskripsi | Aktor | Priority | Status | Tanggal | Tester | Notes | Evidence |
|---------|-------|-----------|-------|----------|--------|--------|--------|-------|----------|
| C-01 | Register Account | Customer dapat mendaftar akun baru | CUSTOMER | HIGH | PENDING | | | | |
| C-02 | Login | Customer dapat login dengan email/username | CUSTOMER | HIGH | PENDING | | | | |

### Formula Excel yang Disediakan:

#### 1. **Status Dropdown (Data Validation)**
```
Validation Type: List
Source: PENDING,PASS,FAIL
```

#### 2. **Formula untuk Hitung Statistik**

Di sheet terpisah atau di bagian bawah, tambahkan:

```excel
=COUNTIF(F:F,"PASS")          // Hitung PASS
=COUNTIF(F:F,"FAIL")          // Hitung FAIL
=COUNTIF(F:F,"PENDING")       // Hitung PENDING
=COUNTA(A:A)-1                // Total (exclude header)
=F2/(COUNTA(A:A)-1)           // Pass Rate
```

#### 3. **Conditional Formatting**

Pada kolom Status:
- **PASS** → Green Background (#10b981)
- **FAIL** → Red Background (#ef4444)
- **PENDING** → Yellow Background (#f59e0b)

#### 4. **Format Priority**

Pada kolom Priority:
- **HIGH** → Red Badge
- **MEDIUM** → Yellow Badge
- **LOW** → Blue Badge

### Kolom-Kolom Excel:

| Kolom | Keterangan | Contoh |
|-------|-----------|--------|
| **A: Test ID** | ID unik test | C-01, C-02, K-01 |
| **B: Fitur** | Nama fitur yang ditest | Login, Add to Cart |
| **C: Deskripsi** | Penjelasan singkat | Customer dapat login dengan email |
| **D: Aktor** | Siapa yang melakukan test | CUSTOMER, CASHIER |
| **E: Priority** | Tingkat prioritas | HIGH, MEDIUM, LOW |
| **F: Status** | Hasil test | PENDING, PASS, FAIL |
| **G: Tanggal** | Tanggal test dijalankan | 2026-02-05 |
| **H: Tester** | Nama orang yang test | Nama Tester |
| **I: Notes** | Catatan tambahan | Bug ditemukan di... |
| **J: Evidence** | Link/screenshot hasil test | C:\screenshots\test-1.png |

### Statistik Otomatis (Summary Sheet)

Buat sheet baru bernama "Summary":

```
=COUNTIF('Testing'!F:F,"PASS")
=COUNTIF('Testing'!F:F,"FAIL")
=COUNTIF('Testing'!F:F,"PENDING")
=COUNTA('Testing'!A:A)-1
=F2/F5*100
```

### Filter & Sort di Excel

1. **Aktifkan AutoFilter**: Data → Filter → AutoFilter
2. **Filter by Status**: Klik dropdown di kolom Status
3. **Filter by Aktor**: Klik dropdown di kolom Aktor
4. **Filter by Priority**: Klik dropdown di kolom Priority

### Print untuk Hardcopy

Format untuk print:
- Landscape mode
- Margin: 0.5 inch
- Scale: 60-80%
- Include header/footer dengan tanggal dan nama file

## Template Data Lengkap

Salin-paste ke Excel Anda:

### CUSTOMER (12 test cases)

```
C-01	Register Account	Customer dapat mendaftar akun baru	CUSTOMER	HIGH	PENDING		
C-02	Login	Customer dapat login dengan email/username	CUSTOMER	HIGH	PENDING		
C-03	Browse Products	Customer dapat melihat daftar produk	CUSTOMER	HIGH	PENDING		
C-04	Search Products	Customer dapat mencari produk	CUSTOMER	HIGH	PENDING		
C-05	View Product Details	Customer dapat melihat detail produk	CUSTOMER	HIGH	PENDING		
C-06	Add to Cart	Customer dapat menambah item ke keranjang	CUSTOMER	HIGH	PENDING		
C-07	View Cart	Customer dapat melihat isi keranjang	CUSTOMER	MEDIUM	PENDING		
C-08	Update Cart Quantity	Customer dapat mengubah jumlah item di cart	CUSTOMER	MEDIUM	PENDING		
C-09	Remove from Cart	Customer dapat menghapus item dari cart	CUSTOMER	MEDIUM	PENDING		
C-10	Checkout	Customer dapat lanjut ke pembayaran	CUSTOMER	HIGH	PENDING		
C-11	Process Payment	Customer dapat melakukan pembayaran	CUSTOMER	HIGH	PENDING		
C-12	View Orders	Customer dapat melihat riwayat pesanan	CUSTOMER	MEDIUM	PENDING		
```

### CASHIER (13 test cases)

```
K-01	Login	Cashier dapat login ke sistem	CASHIER	HIGH	PENDING		
K-02	Create Transaction	Cashier dapat membuat transaksi baru	CASHIER	HIGH	PENDING		
K-03	Add Items (POS)	Cashier dapat menambah item via barcode/code	CASHIER	HIGH	PENDING		
K-04	Check Stock	Sistem cek stock saat add item	CASHIER	HIGH	PENDING		
K-05	Update Item Quantity	Cashier dapat mengubah jumlah item di cart	CASHIER	HIGH	PENDING		
K-06	Remove Item from Cart	Cashier dapat menghapus item dari cart	CASHIER	MEDIUM	PENDING		
K-07	Select Payment Method	Cashier dapat memilih metode pembayaran	CASHIER	HIGH	PENDING		
K-08	Complete Transaction	Cashier dapat menyelesaikan transaksi	CASHIER	HIGH	PENDING		
K-09	Print Receipt	Sistem dapat print struk transaksi	CASHIER	HIGH	PENDING		
K-10	View Transactions	Cashier dapat lihat riwayat transaksi	CASHIER	MEDIUM	PENDING		
K-11	Process Online Orders	Cashier dapat proses pesanan online	CASHIER	HIGH	PENDING		
K-12	Process Marketplace Orders	Cashier dapat proses pesanan marketplace	CASHIER	HIGH	PENDING		
K-13	Logout	Cashier dapat logout dari sistem	CASHIER	MEDIUM	PENDING		
```

### ADMIN (11 test cases)

```
A-01	Login	Admin dapat login ke sistem	ADMIN	HIGH	PENDING		
A-02	Create Item	Admin dapat membuat produk baru	ADMIN	HIGH	PENDING		
A-03	Edit Item	Admin dapat mengubah data produk	ADMIN	HIGH	PENDING		
A-04	Delete Item	Admin dapat menghapus produk	ADMIN	HIGH	PENDING		
A-05	Upload Item Image	Admin dapat upload gambar produk	ADMIN	MEDIUM	PENDING		
A-06	Manage Categories	Admin dapat CRUD kategori	ADMIN	MEDIUM	PENDING		
A-07	Manage Suppliers	Admin dapat CRUD supplier	ADMIN	MEDIUM	PENDING		
A-08	Manage Customers	Admin dapat CRUD customer	ADMIN	MEDIUM	PENDING		
A-09	View Sales Reports	Admin dapat melihat laporan penjualan	ADMIN	MEDIUM	PENDING		
A-10	Filter Reports	Admin dapat filter laporan	ADMIN	MEDIUM	PENDING		
A-11	Export Reports	Admin dapat export laporan	ADMIN	LOW	PENDING		
```

### SUPERVISOR (16 test cases)

```
S-01	Login	Supervisor dapat login ke sistem	SUPERVISOR	HIGH	PENDING		
S-02	Manage Users	Supervisor dapat CRUD user	SUPERVISOR	HIGH	PENDING		
S-03	Assign Roles	Supervisor dapat assign role ke user	SUPERVISOR	HIGH	PENDING		
S-04	Create PR	Supervisor dapat membuat Purchase Request	SUPERVISOR	HIGH	PENDING		
S-05	Approve PR	Supervisor dapat approve PR	SUPERVISOR	HIGH	PENDING		
S-06	Reject PR	Supervisor dapat reject PR	SUPERVISOR	HIGH	PENDING		
S-07	Create PO	Supervisor dapat membuat Purchase Order	SUPERVISOR	HIGH	PENDING		
S-08	Edit PO Prices	Supervisor dapat edit harga di PO	SUPERVISOR	HIGH	PENDING		
S-09	Mark PO as Sent	Supervisor dapat mark PO sudah dikirim	SUPERVISOR	HIGH	PENDING		
S-10	Create Goods Receipt	Supervisor dapat membuat GR	SUPERVISOR	HIGH	PENDING		
S-11	Create Invoice	Supervisor dapat membuat Invoice	SUPERVISOR	HIGH	PENDING		
S-12	Mark Invoice Paid	Supervisor dapat mark Invoice paid	SUPERVISOR	HIGH	PENDING		
S-13	View Stock Movement	Supervisor dapat lihat analisis stok	SUPERVISOR	MEDIUM	PENDING		
S-14	View Procurement Dashboard	Supervisor dapat lihat dashboard procurement	SUPERVISOR	MEDIUM	PENDING		
S-15	View Reports	Supervisor dapat lihat laporan lengkap	SUPERVISOR	MEDIUM	PENDING		
S-16	Logout	Supervisor dapat logout	SUPERVISOR	LOW	PENDING		
```

## Macros Excel (Optional - untuk automasi lebih lanjut)

Jika Anda ingin menggunakan VBA Macro untuk Excel:

```vb
Sub UpdateStats()
    ' Update statistik otomatis
    Dim ws As Worksheet
    Set ws = ThisWorkbook.Sheets("Testing")
    
    Dim totalTests As Long
    Dim passCount As Long
    Dim failCount As Long
    Dim pendingCount As Long
    Dim lastRow As Long
    
    lastRow = ws.Cells(ws.Rows.Count, 1).End(xlUp).Row
    
    totalTests = lastRow - 1 ' Exclude header
    passCount = Application.CountIf(ws.Range("F2:F" & lastRow), "PASS")
    failCount = Application.CountIf(ws.Range("F2:F" & lastRow), "FAIL")
    pendingCount = Application.CountIf(ws.Range("F2:F" & lastRow), "PENDING")
    
    ' Write to Summary sheet
    With ThisWorkbook.Sheets("Summary")
        .Range("B2").Value = totalTests
        .Range("B3").Value = passCount
        .Range("B4").Value = failCount
        .Range("B5").Value = pendingCount
        .Range("B6").Value = passCount / totalTests
    End With
End Sub
```

## Tips Testing Praktis:

1. **Gunakan Template HTML** (testing-checklist.html) jika ingin:
   - Real-time statistics
   - Easy filtering
   - Export langsung ke CSV
   - Akses dari browser

2. **Gunakan Excel** jika ingin:
   - Kerja offline
   - Share ke team via file
   - Analisis lanjut dengan pivot table
   - Print friendly

3. **Best Practice**:
   - Test dengan urutan: Customer → Cashier → Admin → Supervisor
   - Test High Priority dulu sebelum Medium/Low
   - Dokumentasi setiap failure dengan screenshot
   - Catat waktu testing untuk estimasi durasi

---

**Next Steps:**
- Buka file `testing-checklist.html` di browser untuk testing interaktif
- Atau import data di atas ke Excel untuk offline tracking
- Jalankan testing sesuai planning dan mark status real-time
