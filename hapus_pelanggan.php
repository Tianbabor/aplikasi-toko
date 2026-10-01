<?php
require_once 'config/auth.php';
require_once 'config/database.php';
cekLogin();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $koneksi->prepare("DELETE FROM pelanggan WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['sukses'] = 'Data pelanggan berhasil dihapus.';
}

header('Location: data_pelanggan.php');
exit;
