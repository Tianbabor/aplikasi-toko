<?php
// =========================================================
// Helper autentikasi sesi
// =========================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }
}

function namaAdmin() {
    return $_SESSION['nama'] ?? 'Admin';
}
