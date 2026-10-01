<?php
require_once 'config/auth.php';
require_once 'config/database.php';
cekLogin();

$pageTitle  = 'Detail Pelanggan';
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

// Riwayat transaksi pelanggan
$riwayat = $koneksi->prepare("
    SELECT * FROM transaksi WHERE pelanggan_id = ? ORDER BY tanggal_transaksi DESC
");
$riwayat->execute([$id]);
$daftarTransaksi = $riwayat->fetchAll();

$totalTransaksi = count($daftarTransaksi);
$terakhirTransaksi = $totalTransaksi > 0 ? $daftarTransaksi[0]['tanggal_transaksi'] : null;

include 'includes/header.php';
?>

<a href="data_pelanggan.php" class="text-decoration-none text-muted small">
    <i class="bi bi-arrow-left"></i> Kembali
</a>
<h4 class="fw-bold mt-2 mb-4">Detail Pelanggan</h4>

<div class="card-box mb-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="stat-icon blue" style="width:56px;height:56px;font-size:26px;">
            <i class="bi bi-person"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-0"><?= htmlspecialchars($p['nama_pelanggan']) ?></h5>
            <span class="text-muted"><?= htmlspecialchars($p['kode_pelanggan']) ?></span>
            <?php if ($p['status'] === 'Aktif'): ?>
                <span class="badge-aktif ms-1">Aktif</span>
            <?php else: ?>
                <span class="badge-tidak-aktif ms-1">Tidak Aktif</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="text-muted small">Nama</div>
            <div class="fw-semibold"><?= htmlspecialchars($p['nama_pelanggan']) ?></div>
        </div>
        <div class="col-md-6">
            <div class="text-muted small">Tanggal Daftar</div>
            <div class="fw-semibold"><?= date('d M Y', strtotime($p['tanggal_daftar'])) ?></div>
        </div>
        <div class="col-md-6">
            <div class="text-muted small">No. HP</div>
            <div class="fw-semibold"><?= htmlspecialchars($p['no_hp']) ?></div>
        </div>
        <div class="col-md-6">
            <div class="text-muted small">Total Transaksi</div>
            <div class="fw-semibold"><?= $totalTransaksi ?> kali</div>
        </div>
        <div class="col-md-6">
            <div class="text-muted small">Email</div>
            <div class="fw-semibold"><?= htmlspecialchars($p['email'] ?: '-') ?></div>
        </div>
        <div class="col-md-6">
            <div class="text-muted small">Terakhir Transaksi</div>
            <div class="fw-semibold"><?= $terakhirTransaksi ? date('d M Y', strtotime($terakhirTransaksi)) : '-' ?></div>
        </div>
        <div class="col-md-12">
            <div class="text-muted small">Alamat</div>
            <div class="fw-semibold"><?= htmlspecialchars($p['alamat'] ?: '-') ?></div>
        </div>
    </div>
</div>

<div class="card-box">
    <h6 class="fw-bold mb-3">Riwayat Transaksi</h6>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr class="text-muted">
                    <th>No</th><th>Tanggal</th><th>Total Belanja</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($daftarTransaksi as $t): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d M Y', strtotime($t['tanggal_transaksi'])) ?></td>
                    <td>Rp <?= number_format($t['total_belanja'], 0, ',', '.') ?></td>
                    <td><span class="badge-selesai"><?= htmlspecialchars($t['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($daftarTransaksi)): ?>
                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada transaksi</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
