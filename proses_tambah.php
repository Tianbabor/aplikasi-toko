<?php
require_once 'config/auth.php';
require_once 'config/database.php';
cekLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode   = trim($_POST['kode_pelanggan'] ?? '');
    $nama   = trim($_POST['nama_pelanggan'] ?? '');
    $hp     = trim($_POST['no_hp'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $status = $_POST['status'] ?? 'Aktif';
    $tanggal = $_POST['tanggal_daftar'] ?? date('Y-m-d');

    if ($kode === '' || $nama === '' || $hp === '') {
        $_SESSION['form_error'] = 'Kode pelanggan, nama, dan no. HP wajib diisi.';
        header('Location: tambah_pelanggan.php');
        exit;
    }

    // Cek kode pelanggan sudah ada atau belum
    $cek = $koneksi->prepare("SELECT id FROM pelanggan WHERE kode_pelanggan = ?");
    $cek->execute([$kode]);
    if ($cek->fetch()) {
        $_SESSION['form_error'] = 'Kode pelanggan sudah digunakan.';
        header('Location: tambah_pelanggan.php');
        exit;
    }

    $stmt = $koneksi->prepare("
        INSERT INTO pelanggan (kode_pelanggan, nama_pelanggan, no_hp, email, alamat, tanggal_daftar, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$kode, $nama, $hp, $email ?: null, $alamat, $tanggal, $status]);

    $_SESSION['sukses'] = 'Data pelanggan berhasil ditambahkan.';
    header('Location: data_pelanggan.php');
    exit;
}

header('Location: tambah_pelanggan.php');
exit;
