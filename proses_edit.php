<?php
require_once 'config/auth.php';
require_once 'config/database.php';
cekLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $kode   = trim($_POST['kode_pelanggan'] ?? '');
    $nama   = trim($_POST['nama_pelanggan'] ?? '');
    $hp     = trim($_POST['no_hp'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $status = $_POST['status'] ?? 'Aktif';

    if ($kode === '' || $nama === '' || $hp === '') {
        $_SESSION['form_error'] = 'Kode pelanggan, nama, dan no. HP wajib diisi.';
        header('Location: edit_pelanggan.php?id=' . $id);
        exit;
    }

    // Cek kode pelanggan tidak dipakai pelanggan lain
    $cek = $koneksi->prepare("SELECT id FROM pelanggan WHERE kode_pelanggan = ? AND id != ?");
    $cek->execute([$kode, $id]);
    if ($cek->fetch()) {
        $_SESSION['form_error'] = 'Kode pelanggan sudah digunakan pelanggan lain.';
        header('Location: edit_pelanggan.php?id=' . $id);
        exit;
    }

    $stmt = $koneksi->prepare("
        UPDATE pelanggan
        SET kode_pelanggan = ?, nama_pelanggan = ?, no_hp = ?, email = ?, alamat = ?, status = ?
        WHERE id = ?
    ");
    $stmt->execute([$kode, $nama, $hp, $email ?: null, $alamat, $status, $id]);

    $_SESSION['sukses'] = 'Data pelanggan berhasil diperbarui.';
    header('Location: data_pelanggan.php');
    exit;
}

header('Location: data_pelanggan.php');
exit;
