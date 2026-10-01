<?php
require_once 'config/auth.php';
require_once 'config/database.php';
cekLogin();

$pageTitle  = 'Data Pelanggan';
$activeMenu = 'pelanggan';

// Notifikasi
$sukses = $_SESSION['sukses'] ?? '';
unset($_SESSION['sukses']);

// Pencarian
$cari = trim($_GET['cari'] ?? '');

// Pagination
$limit = 5;
$halaman = max(1, (int)($_GET['halaman'] ?? 1));
$offset = ($halaman - 1) * $limit;

if ($cari !== '') {
    $sqlWhere = "WHERE nama_pelanggan LIKE :cari OR no_hp LIKE :cari OR alamat LIKE :cari";
    $params = [':cari' => "%$cari%"];
} else {
    $sqlWhere = "";
    $params = [];
}

$totalData = $koneksi->prepare("SELECT COUNT(*) c FROM pelanggan $sqlWhere");
$totalData->execute($params);
$totalRow = $totalData->fetch()['c'];
$totalHalaman = max(1, ceil($totalRow / $limit));

$stmt = $koneksi->prepare("SELECT * FROM pelanggan $sqlWhere ORDER BY id ASC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$daftarPelanggan = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Data Pelanggan</h4>
    <a href="tambah_pelanggan.php" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Pelanggan
    </a>
</div>

<?php if ($sukses): ?>
    <div class="alert alert-success"><?= htmlspecialchars($sukses) ?></div>
<?php endif; ?>

<div class="card-box">
    <form method="GET" class="mb-3">
        <div class="input-group" style="max-width:350px;">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="text" name="cari" class="form-control" placeholder="Cari nama, nomor HP, atau alamat..." value="<?= htmlspecialchars($cari) ?>">
        </div>
    </form>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr class="text-muted">
                    <th>No</th>
                    <th>Kode Pelanggan</th>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = $offset + 1; foreach ($daftarPelanggan as $p): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($p['kode_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($p['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($p['no_hp']) ?></td>
                    <td><?= htmlspecialchars($p['alamat']) ?></td>
                    <td><?= date('d M Y', strtotime($p['tanggal_daftar'])) ?></td>
                    <td>
                        <?php if ($p['status'] === 'Aktif'): ?>
                            <span class="badge-aktif">Aktif</span>
                        <?php else: ?>
                            <span class="badge-tidak-aktif">Tidak Aktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="detail_pelanggan.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary btn-icon" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="edit_pelanggan.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary btn-icon" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="hapus_pelanggan.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger btn-icon" title="Hapus"
                           onclick="return confirm('Yakin ingin menghapus pelanggan ini?');">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($daftarPelanggan)): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada data ditemukan</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <span class="text-muted small">
            Menampilkan <?= min($offset + 1, $totalRow) ?>-<?= min($offset + $limit, $totalRow) ?> dari <?= $totalRow ?> data
        </span>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
                    <li class="page-item <?= $i === $halaman ? 'active' : '' ?>">
                        <a class="page-link" href="?halaman=<?= $i ?>&cari=<?= urlencode($cari) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
