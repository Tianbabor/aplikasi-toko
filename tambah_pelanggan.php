<?php
require_once 'config/auth.php';
require_once 'config/database.php';
cekLogin();

$pageTitle  = 'Tambah Pelanggan';
$activeMenu = 'pelanggan';

$error = $_SESSION['form_error'] ?? '';
unset($_SESSION['form_error']);

// Saran kode pelanggan otomatis (PLGxxx)
$last = $koneksi->query("SELECT kode_pelanggan FROM pelanggan ORDER BY id DESC LIMIT 1")->fetch();
$nextNumber = 1;
if ($last) {
    $nextNumber = (int)substr($last['kode_pelanggan'], 3) + 1;
}
$saranKode = 'PLG' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

include 'includes/header.php';
?>

<a href="data_pelanggan.php" class="text-decoration-none text-muted small">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
<h4 class="fw-bold mt-2 mb-4">Tambah Pelanggan</h4>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card-box">
    <h6 class="fw-bold mb-3">Form Tambah Pelanggan</h6>
    <form action="proses_tambah.php" method="POST">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Kode Pelanggan</label>
                <input type="text" name="kode_pelanggan" class="form-control" placeholder="Contoh: <?= $saranKode ?>" value="<?= $saranKode ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat lengkap">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="form-control" placeholder="Masukkan nama pelanggan" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email (opsional)">
            </div>
            <div class="col-md-6">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control" placeholder="Masukkan nomor HP" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Aktif" selected>Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Daftar</label>
                <input type="date" name="tanggal_daftar" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>

        <div class="mt-4">
            <a href="data_pelanggan.php" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
