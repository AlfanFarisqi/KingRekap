# King Rekap

**Sistem Informasi Pencatatan dan Rekapitulasi Penjualan UMKM Es Teler Berbasis Web**

## Deskripsi Project

**King Rekap** adalah sistem informasi berbasis web yang dirancang untuk membantu UMKM Es Teler dalam melakukan pencatatan dan rekapitulasi data penjualan.

Sistem ini memungkinkan pengguna untuk memasukkan data penjualan yang telah dilakukan, menyimpan riwayat penjualan, serta mengolah data tersebut menjadi rekap penjualan berdasarkan periode tertentu.

King Rekap tidak berfungsi sebagai sistem kasir atau pembayaran. Sistem berfokus pada **pencatatan data penjualan dan pengolahan data menjadi informasi penjualan** yang lebih mudah dipantau oleh pemilik UMKM.

## Tujuan

Project ini bertujuan untuk:

* Mempermudah pencatatan data penjualan.
* Menyimpan data penjualan secara terstruktur.
* Mempermudah pencarian riwayat penjualan.
* Menghasilkan rekap penjualan secara otomatis.
* Menampilkan informasi produk yang paling banyak terjual.
* Membantu pemilik UMKM memantau perkembangan penjualan.

## Target Pengguna

Target pengguna King Rekap adalah:

* Pemilik UMKM Es Teler.
* Pengelola atau karyawan yang bertugas mencatat penjualan.

## Fitur Utama

### 1. Login

Pengguna melakukan login sebelum mengakses sistem.

### 2. Dashboard

Menampilkan ringkasan data penjualan, seperti:

* Total penjualan.
* Jumlah penjualan.
* Jumlah produk terjual.
* Produk terlaris.
* Grafik penjualan.

### 3. Data Menu

Digunakan untuk mengelola daftar produk yang dijual.

Fitur:

* Tambah data menu.
* Edit data menu.
* Hapus data menu.
* Melihat data menu.

### 4. Pencatatan Penjualan

Pengguna dapat memasukkan data penjualan yang telah dilakukan.

Data yang dicatat meliputi:

* Tanggal penjualan.
* Nama produk.
* Jumlah produk terjual.
* Harga produk.
* Total penjualan.

### 5. Riwayat Penjualan

Menampilkan seluruh data penjualan yang telah dicatat.

Pengguna dapat melihat detail dan mencari data penjualan berdasarkan periode.

### 6. Rekap Penjualan

Sistem mengolah data penjualan menjadi rekap berdasarkan:

* Harian.
* Mingguan.
* Bulanan.
* Periode tanggal tertentu.

Rekap menampilkan total penjualan, jumlah produk terjual, jumlah transaksi pencatatan, dan produk terlaris.

### 7. Grafik Penjualan

Menampilkan perkembangan penjualan dalam bentuk grafik agar data lebih mudah dipahami.

### 8. Laporan Penjualan

Pengguna dapat melihat dan mencetak laporan penjualan berdasarkan periode yang dipilih.

## Teknologi yang Digunakan

* **Laravel** sebagai framework pengembangan sistem.
* **PHP** sebagai bahasa pemrograman.
* **Blade** sebagai template engine untuk tampilan.
* **HTML** untuk struktur halaman.
* **CSS / Bootstrap** untuk tampilan antarmuka.
* **MySQL** sebagai database.
* **Visual Studio Code** sebagai code editor.
* **Git dan GitHub** untuk pengelolaan source code.

## Gambaran Alur Sistem

```text
Login
  ↓
Dashboard
  ↓
Data Menu
  ↓
Pencatatan Penjualan
  ↓
Data Penjualan Tersimpan
  ↓
Riwayat Penjualan
  ↓
Rekap Penjualan
  ↓
Grafik & Laporan
```

# Pembagian Jobdesk

## 1. Project Manager (PM)

**Tanggung Jawab Utama:**
Mengatur dan mengoordinasikan seluruh proses pengembangan King Rekap.

**Jobdesk:**

* Menentukan pembagian tugas anggota.
* Membuat jadwal dan target pengerjaan.
* Mengatur koordinasi antaranggota.
* Melakukan komunikasi dengan pihak UMKM.
* Mengumpulkan informasi kebutuhan sistem dari UMKM.
* Memantau perkembangan setiap bagian proyek.
* Mengatur dokumentasi proyek.
* Membantu melakukan testing sistem.
* Memastikan fitur yang dibuat sesuai kebutuhan UMKM.
* Menyiapkan materi presentasi dan laporan proyek.

## 2. Front End Developer (FE)

**Tanggung Jawab Utama:**
Membuat tampilan dan antarmuka website King Rekap.

**Jobdesk:**

* Membuat desain tampilan website.
* Membuat halaman login.
* Membuat halaman dashboard.
* Membuat halaman data menu.
* Membuat halaman input penjualan.
* Membuat halaman riwayat penjualan.
* Membuat halaman rekap penjualan.
* Membuat tampilan grafik penjualan.
* Membuat halaman laporan.
* Memastikan website nyaman dan mudah digunakan.
* Membuat tampilan responsive untuk berbagai ukuran layar.

## 3. Back End Developer (BE)

**Tanggung Jawab Utama:**
Mengembangkan fungsi sistem, database, dan proses pengolahan data King Rekap.

**Jobdesk:**

* Membuat dan mengatur database MySQL.
* Membuat migration dan model Laravel.
* Membuat sistem login dan autentikasi.
* Membuat fungsi pengelolaan data menu.
* Membuat fungsi pencatatan penjualan.
* Menyimpan data penjualan ke database.
* Membuat fungsi riwayat penjualan.
* Membuat proses rekapitulasi penjualan.
* Menghitung total penjualan berdasarkan periode.
* Mengolah data untuk menentukan produk terlaris.
* Menyediakan data untuk grafik penjualan.
* Membuat fungsi laporan penjualan.
* Melakukan validasi data dan pengujian fungsi sistem.

# Alur Kerja Tim

```text
             PROJECT MANAGER
                    │
          ┌─────────┴─────────┐
          ↓                   ↓
     FRONT END            BACK END
          │                   │
          └─────────┬─────────┘
                    ↓
              KING REKAP
                    ↓
             Testing & Evaluasi
                    ↓
              Sistem Selesai
```

PM mengatur dan memantau proyek. FE mengembangkan tampilan. BE mengembangkan fungsi dan database. Ketiga anggota bekerja sama dalam proses integrasi, testing, dokumentasi, dan presentasi.

## Batasan Project

King Rekap hanya berfokus pada pencatatan dan rekapitulasi penjualan. Sistem tidak mencakup:

* Pembayaran online.
* Pengelolaan stok bahan baku.
* Sistem pengiriman.
* Akun pelanggan.
* Program loyalitas pelanggan.
* Prediksi penjualan menggunakan AI.

## Anggota Tim

| Nama                  | Role                |
| --------------------- | ------------------- |
| Alfan Pratama Farisqi | Project Manager     |
| Dela Audi Setyawati   | Front End Developer |
| Amanda Silfiana       | Back End Developer  |
