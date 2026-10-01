# TokoKu - Sistem Informasi Pengelolaan Data Pelanggan

Aplikasi web berbasis **PHP native + MySQL (PDO) + Bootstrap 5**, sesuai desain UI dan ERD yang diberikan.
Setiap fitur dipisah dalam file-nya sendiri (tidak digabung dalam satu file).

## Struktur File

```
tokoku/
├── config/
│   ├── database.php        -> koneksi PDO ke MySQL
│   └── auth.php             -> helper session & cek login
├── includes/
│   ├── header.php           -> layout atas + topbar
│   ├── sidebar.php          -> menu sidebar
│   └── footer.php           -> penutup layout + script
├── assets/
│   ├── css/style.css        -> styling custom (tema sesuai mockup)
│   └── js/                  -> (tempat script tambahan bila perlu)
├── database.sql             -> struktur tabel + data contoh
├── setup_admin.php          -> buat password admin (jalankan 1x)
├── index.php                -> halaman Login
├── login_process.php        -> proses login
├── logout.php                -> proses logout
├── dashboard.php             -> Dashboard (statistik + grafik)
├── data_pelanggan.php        -> daftar pelanggan (search + pagination)
├── tambah_pelanggan.php      -> form tambah pelanggan
├── proses_tambah.php         -> proses simpan data baru
├── edit_pelanggan.php        -> form edit pelanggan
├── proses_edit.php           -> proses update data
├── hapus_pelanggan.php       -> proses hapus data
├── detail_pelanggan.php      -> detail + riwayat transaksi pelanggan
└── laporan.php                -> laporan pelanggan per periode + cetak
```

## Cara Menjalankan (XAMPP / Laragon)

1. Copy folder `tokoku` ke dalam `htdocs` (XAMPP) atau `www` (Laragon).
2. Buka **phpMyAdmin**, buat/pastikan MySQL aktif, lalu import file `database.sql`
   (ini otomatis membuat database `tokoku` beserta tabel dan data contoh).
3. Cek `config/database.php` — sesuaikan `DB_USER`/`DB_PASS` jika bukan default XAMPP (`root` / tanpa password).
4. Buka di browser: `http://localhost/tokoku/setup_admin.php`
   Isi username `admin` dan password (default disarankan: `admin123`), klik **Simpan Password**.
   **Setelah itu hapus file `setup_admin.php` dari server.**
5. Buka `http://localhost/tokoku/index.php` dan login dengan username & password yang baru dibuat.

## Struktur Database (sesuai ERD)

- `users` — data admin (login)
- `pelanggan` — data pelanggan (kode, nama, HP, email, alamat, status)
- `produk` — data produk toko
- `transaksi` — transaksi per pelanggan
- `detail_transaksi` — item per transaksi
- `riwayat_transaksi` — log riwayat (opsional, mengikuti ERD)

## Fitur yang Sudah Tersedia

- Login admin (session, password ter-hash bcrypt)
- Dashboard: total pelanggan, pelanggan baru bulan ini, total transaksi, pelanggan aktif, tabel pelanggan terbaru, grafik (Chart.js)
- Data Pelanggan: list, cari, pagination, tambah, edit, hapus (dengan konfirmasi)
- Detail Pelanggan: profil lengkap + riwayat transaksi
- Laporan: filter periode tanggal + cetak (print-friendly)

## Pengembangan Lanjutan (opsional, sesuai flowchart)

- CRUD produk & transaksi (tabel `produk`, `transaksi`, `detail_transaksi` sudah disiapkan)
- Export laporan ke PDF/Excel
- Role multi-admin
