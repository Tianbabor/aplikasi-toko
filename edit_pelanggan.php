<?php
require_once 'config/auth.php';
require_once 'config/database.php';
cekLogin();

$pageTitle  = 'Edit Pelanggan';
$activeMenu = 'pelanggan';

$id = (int)($_GET['id'] ?? 0);
$stmt = $koneksi->prepare("SELECT * FROM pelanggan WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
    $_SESSION['sukses'] = 'Data pelanggan tidak ditemukan.';
    header('Location: data_pelanggan.php');
    exit;
}

$error = $_SESSION['form_error'] ?? '';
unset($_SESSION['form_error']);

include 'includes/header.php';
?>

<a href="data_pelanggan.php" class="text-decoration-none text-muted small">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
<h4 class="fw-bold mt-2 mb-4">Edit Pelanggan</h4>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card-box">
    <h6 class="fw-bold mb-3">Form Edit Pelanggan</h6>
    <form action="proses_edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $p['id'] ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Kode Pelanggan</label>
                <input type="text" name="kode_pelanggan" class="form-control" value="<?= htmlspecialchars($p['kode_pelanggan']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Alamat</label>
                <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($p['alamat']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="form-control" value="<?= htmlspecialchars($p['nama_pelanggan']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($p['email'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($p['no_hp']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Aktif" <?= $p['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Tidak Aktif" <?= $p['status'] === 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <a href="data_pelanggan.php" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
