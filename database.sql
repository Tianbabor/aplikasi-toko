-- =========================================================
-- TokoKu - Sistem Informasi Pengelolaan Data Pelanggan
-- Database Schema (sesuai ERD)
-- =========================================================

CREATE DATABASE IF NOT EXISTS tokoku;
USE tokoku;

-- Tabel users (admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel pelanggan
CREATE TABLE pelanggan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_pelanggan VARCHAR(20) NOT NULL UNIQUE,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    alamat TEXT,
    tanggal_daftar DATE NOT NULL,
    status ENUM('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel produk
CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    stok INT NOT NULL DEFAULT 0,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel transaksi
CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelanggan_id INT NOT NULL,
    user_id INT NOT NULL,
    tanggal_transaksi DATE NOT NULL,
    total_belanja DECIMAL(12,2) NOT NULL DEFAULT 0,
    metode_pembayaran VARCHAR(50) DEFAULT 'Tunai',
    status ENUM('Selesai','Diproses','Batal') NOT NULL DEFAULT 'Selesai',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabel detail_transaksi
CREATE TABLE detail_transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT NOT NULL,
    produk_id INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    harga_satuan DECIMAL(12,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id)
);

-- Tabel riwayat_transaksi (log ringkasan, opsional sesuai ERD)
CREATE TABLE riwayat_transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelanggan_id INT NOT NULL,
    tanggal_transaksi DATE NOT NULL,
    keterangan VARCHAR(255) DEFAULT NULL,
    total_belanja DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id) ON DELETE CASCADE
);

-- =========================================================
-- Data awal (sesuai mockup)
-- =========================================================

-- Akun admin awal dibuat lewat setup_admin.php (bukan hash statis di sini),
-- supaya password "admin123" pasti valid pada versi PHP di server Anda.
INSERT INTO users (nama, email, username, password, role) VALUES
('Admin TokoKu', 'admin@tokoku.com', 'admin', 'BELUM_DIATUR', 'admin');
-- SETELAH import database ini, buka setup_admin.php di browser SATU KALI
-- untuk membuat password admin (default: admin123), lalu hapus file itu.

INSERT INTO pelanggan (kode_pelanggan, nama_pelanggan, no_hp, email, alamat, tanggal_daftar, status) VALUES
('PLG001', 'Andi Saputra',   '0812-3456-7890', 'andi@email.com',   'Jl. Melati No. 12',    '2025-04-12', 'Aktif'),
('PLG002', 'Siti Nurhaliza', '0813-9876-5432', 'siti@email.com',   'Jl. Kenanga No. 5',    '2025-04-10', 'Aktif'),
('PLG003', 'Budi Santoso',   '0852-1111-2222', 'budi@email.com',   'Jl. Anggrek No. 8',    '2025-04-08', 'Aktif'),
('PLG004', 'Dewi Lestari',   '0819-3333-4444', 'dewi@email.com',   'Jl. Dahlia No. 3',     '2025-04-05', 'Aktif'),
('PLG005', 'Rudi Hermawan',  '0821-5555-6666', 'rudi@email.com',   'Jl. Cempaka No. 7',    '2025-04-02', 'Aktif');

INSERT INTO produk (nama_produk, harga, stok, deskripsi) VALUES
('Produk A', 50000, 100, 'Produk contoh A'),
('Produk B', 80000, 50,  'Produk contoh B'),
('Produk C', 120000, 30, 'Produk contoh C');

INSERT INTO transaksi (pelanggan_id, user_id, tanggal_transaksi, total_belanja, metode_pembayaran, status) VALUES
(1, 1, '2025-04-20', 250000, 'Tunai',        'Selesai'),
(1, 1, '2025-04-12', 160000, 'Transfer',     'Selesai'),
(1, 1, '2025-04-05', 320000, 'Tunai',        'Selesai');
