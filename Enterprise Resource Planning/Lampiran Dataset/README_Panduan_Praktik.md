# Panduan Praktik ERPNext - Tipe Industri Manufaktur
**Studi Kasus: PT Alfina Manufaktur Indonesia (AMI)**

Folder ini berisi dataset sederhana yang siap digunakan untuk latihan dan simulasi implementasi modul **Manufacturing (Manufaktur)**, **Stock (Persediaan)**, **Selling (Penjualan)**, dan **Buying (Pembelian)** pada ERPNext.

---

## 1. Profil Perusahaan & Skenario Produk

- **Nama Perusahaan**: PT Alfina Manufaktur Indonesia (AMI)
- **Bidang Usaha**: Industri Manufaktur Furnitur Kayu
- **Produk Jadi (Finished Goods)**:
  - `FG-MJ-001` : Meja Kayu Minimalis (Harga Jual: Rp 750.000 / unit)

- **Bahan Baku (Raw Materials)**:
  1. `RM-KY-001` : Papan Kayu Jati 2m x 1m (Butuh 2 Meter per unit meja)
  2. `RM-KJ-002` : Kaki Meja Kayu (Butuh 4 Nos per unit meja)
  3. `RM-SK-003` : Set Sekrup & Baut (Butuh 1 Set per unit meja)
  4. `RM-CT-004` : Cat Vernis Pelapis Kayu (Butuh 1 Liter per unit meja)

- **Gudang (Warehouses)**:
  - `Gudang Bahan Baku` : Menyimpan material mentah dari Supplier
  - `Gudang Barang Dalam Proses` : Memproses komponen dalam proses manufaktur (WIP)
  - `Gudang Barang Jadi` : Menyimpan Meja Kayu Minimalis yang siap dijual

- **Stasiun Kerja & Operasi (Workstations & Operations)**:
  - `WS-POTONG` (Rate: Rp 50.000/jam) -> Operasi: `OP-CUTTING` (30 menit)
  - `WS-RAKIT` (Rate: Rp 45.000/jam) -> Operasi: `OP-ASSEMBLY` (45 menit)
  - `WS-FINISHING` (Rate: Rp 40.000/jam) -> Operasi: `OP-FINISHING` (60 menit)

---

## 2. Struktur File Dataset

| Nama File | Deskripsi | DocType ERPNext |
| :--- | :--- | :--- |
| `01_Item_Groups.csv` | Master Kelompok Barang (Bahan Baku, WIP, Barang Jadi) | Item Group |
| `02_Item_Master.csv` | Master Kode, Nama, UOM, dan Harga Standar Barang | Item |
| `03_Warehouse_Master.csv` | Master Lokasi Gudang Persediaan | Warehouse |
| `04_Supplier_Master.csv` | Master Pemasok Bahan Baku | Supplier |
| `05_Customer_Master.csv` | Master Pelanggan / Pembeli | Customer |
| `06_Workstation_Operations.csv` | Master Stasiun Kerja & Jenis Operasi Manufaktur | Workstation & Operation |
| `07_BOM_Master.csv` | Resep Produksi / Bill of Materials | BOM |
| `08_Initial_Stock_Entry.csv` | Template Saldo Awal Bahan Baku di Gudang | Stock Entry |
| `09_Sales_Order_Sample.csv` | Contoh Pesanan Penjualan dari Pelanggan | Sales Order |
| `Dataset_Praktik_Manufaktur_ERPNext.xlsx` | File Excel konsolidasi (Seluruh sheet gabungan) | Master Excel |

---

## 3. Urutan Import Data ke ERPNext (Data Import Tool)

Buka ERPNext, ketik **Data Import** di Search Bar, kemudian lakukan pengimporan sesuai urutan berikut untuk menghindari error dependensi:

1. **Item Group**: Impor `01_Item_Groups.csv` -> DocType: `Item Group`
2. **Item Master**: Impor `02_Item_Master.csv` -> DocType: `Item`
3. **Warehouse**: Impor `03_Warehouse_Master.csv` -> DocType: `Warehouse`
4. **Supplier**: Impor `04_Supplier_Master.csv` -> DocType: `Supplier`
5. **Customer**: Impor `05_Customer_Master.csv` -> DocType: `Customer`
6. **Workstation & Operation**: 
   - Masukkan Workstation (`WS-POTONG`, `WS-RAKIT`, `WS-FINISHING`) di menu **Manufacturing > Workstation**.
   - Masukkan Operation (`OP-CUTTING`, `OP-ASSEMBLY`, `OP-FINISHING`) di menu **Manufacturing > Operation**.
7. **Bill of Materials (BOM)**:
   - Buka menu **Manufacturing > Bill of Materials (BOM) > New**.
   - Pilih Item: `FG-MJ-001` (Quantity: 1).
   - Masukkan tabel **Items** (Bahan Baku `RM-KY-001`, `RM-KJ-002`, `RM-SK-003`, `RM-CT-004` sesuai kuantitas resep).
   - Masukkan tabel **Operations** (`OP-CUTTING`, `OP-ASSEMBLY`, `OP-FINISHING` dengan Workstation masing-masing).
   - Centang **Is Active** dan **Is Default**, lalu **Submit**.

---

## 4. Alur Skenario Praktik Manufaktur (Step-by-Step)

### Skenario 1: Penerimaan Stock Awal Bahan Baku
1. Buka **Stock > Stock Entry > New**.
2. Pilih Stock Entry Type: **Material Receipt**.
3. Set Default Target Warehouse: **Gudang Bahan Baku**.
4. Masukkan item bahan baku (`RM-KY-001`, `RM-KJ-002`, `RM-SK-003`, `RM-CT-004`) beserta jumlah dan harga pokok awal (sesuai file `08_Initial_Stock_Entry.csv`).
5. Klik **Save** dan **Submit**.

### Skenario 2: Penerimaan Sales Order (Pesanan Penjualan)
1. Buka **Selling > Sales Order > New**.
2. Pilih Customer: `PT Mebel Sejahtera`.
3. Tambahkan Item: `FG-MJ-001` sebanyak **10 unit** (sesuai file `09_Sales_Order_Sample.csv`).
4. Klik **Save** dan **Submit**.

### Skenario 3: Perencanaan & Pelaksanaan Produksi (Work Order)
1. Dari halaman **Sales Order** yang sudah disubmit, klik tombol **Create > Work Order** (atau buat manual dari menu **Manufacturing > Work Order**).
2. Pastikan:
   - Production Item: `FG-MJ-001`
   - Qty To Manufacture: `10`
   - Target Warehouse: `Gudang Barang Jadi`
   - WIP Warehouse: `Gudang Barang Dalam Proses`
3. Klik **Save** dan **Submit**. Status Work Order menjadi **Not Started**.

### Skenario 4: Transfer Bahan Baku ke Gudang WIP
1. Pada Work Order, klik **Start**. ERPNext akan membuat **Stock Entry** tipe **Material Transfer for Manufacture**.
2. Periksa bahwa Source Warehouse adalah `Gudang Bahan Baku` dan Target Warehouse adalah `Gudang Barang Dalam Proses`.
3. Klik **Submit**. Status Work Order berubah menjadi **In Process**.

### Skenario 5: Pengerjaan Operasi Produksi (Job Card / Operations)
1. Buka **Manufacturing > Job Card**.
2. Buka masing-masing Job Card (Cutting, Assembly, Finishing) lalu catat waktu mulai (**Start**) dan selesai (**Complete**).

### Skenario 6: Penyelesaian Produksi (Finish Manufacture)
1. Pada Work Order, klik **Finish**. ERPNext akan membuat **Stock Entry** tipe **Manufacture**.
2. Sistem akan memindahkan material dari `Gudang Barang Dalam Proses` menjadi produk jadi `FG-MJ-001` sebanyak 10 unit di `Gudang Barang Jadi`.
3. Klik **Submit**. Status Work Order berubah menjadi **Completed**.

### Skenario 7: Pengiriman & Penagihan ke Customer
1. Buka kembali **Sales Order** `PT Mebel Sejahtera`.
2. Klik **Create > Delivery Note** (Pengiriman Barang Jadi dari `Gudang Barang Jadi`). Submit.
3. Klik **Create > Sales Invoice** (Faktur Penjualan). Submit dan catat Pembayaran (**Payment Entry**).

---
*Dataset ini dirancang khusus untuk memenuhi kebutuhan latihan praktikum Enterprise Resource Planning (ERPNext) modul Manufaktur.*
