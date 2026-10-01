<?php
require_once 'config/auth.php';
require_once 'config/database.php';
cekLogin();

$pageTitle  = 'Laporan';
$activeMenu = 'laporan';

$dariTanggal    = $_GET['dari']  ?? date('Y-m-01');
$sampaiTanggal  = $_GET['sampai'] ?? date('Y-m-d');

$stmt = $koneksi->prepare("
    SELECT * FROM pelanggan
    WHERE tanggal_daftar BETWEEN ? AND ?
    ORDER BY tanggal_daftar ASC
");
$stmt->execute([$dariTanggal, $sampaiTanggal]);
$dataLaporan = $stmt->fetchAll();

// ---- Data untuk grafik: jumlah pelanggan baru per tanggal ----
$grafikStmt = $koneksi->prepare("
    SELECT tanggal_daftar, COUNT(*) AS jumlah
    FROM pelanggan
    WHERE tanggal_daftar BETWEEN ? AND ?
    GROUP BY tanggal_daftar
    ORDER BY tanggal_daftar ASC
");
$grafikStmt->execute([$dariTanggal, $sampaiTanggal]);
$grafikRows = $grafikStmt->fetchAll();

$grafikLabels = [];
$grafikValues = [];
foreach ($grafikRows as $row) {
    $grafikLabels[] = date('d M', strtotime($row['tanggal_daftar']));
    $grafikValues[] = (int) $row['jumlah'];
}

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h4 class="fw-bold mb-0">Laporan Data Pelanggan</h4>
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
</div>

<div class="card-box mb-3 no-print">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="dari" class="form-control" value="<?= htmlspecialchars($dariTanggal) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="sampai" class="form-control" value="<?= htmlspecialchars($sampaiTanggal) ?>">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-outline-primary w-100">Tampilkan</button>
        </div>
    </form>
</div>

<!-- ================= GRAFIK ================= -->
<div class="card-box mb-3 no-print">
    <h6 class="fw-bold mb-3">Grafik Pelanggan Baru per Tanggal</h6>
    <?php if (!empty($grafikValues)): ?>
        <canvas id="chartPelanggan" height="90"></canvas>
    <?php else: ?>
        <p class="text-muted text-center py-3 mb-0">Tidak ada data untuk ditampilkan pada grafik di periode ini.</p>
    <?php endif; ?>
</div>
<!-- ================= /GRAFIK ================= -->

<div class="card-box" id="areaCetak">
    <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-3">
        <i class="bi bi-shop fs-3 text-primary"></i>
        <div>
            <h5 class="fw-bold mb-0">TokoKu</h5>
            <small class="text-muted">Jl. Contoh No. 123, Jakarta</small>
        </div>
    </div>

    <h5 class="fw-bold mb-0">Laporan Data Pelanggan</h5>
    <p class="text-muted">
        Periode: <?= date('d M Y', strtotime($dariTanggal)) ?> - <?= date('d M Y', strtotime($sampaiTanggal)) ?>
    </p>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr class="text-muted">
                    <th>No</th><th>Kode Pelanggan</th><th>Nama</th><th>No. HP</th><th>Alamat</th><th>Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($dataLaporan as $p): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($p['kode_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($p['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($p['no_hp']) ?></td>
                    <td><?= htmlspecialchars($p['alamat']) ?></td>
                    <td><?= date('d M Y', strtotime($p['tanggal_daftar'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($dataLaporan)): ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data pada periode ini</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-end mt-4">
        <p class="mb-0">Jakarta, <?= date('d M Y') ?></p>
        <p class="fw-semibold">Admin TokoKu</p>
    </div>
</div>

<?php if (!empty($grafikValues)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartPelanggan'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($grafikLabels) ?>,
        datasets: [{
            label: 'Pelanggan Baru',
            data: <?= json_encode($grafikValues) ?>,
            backgroundColor: '#2563eb',
            borderRadius: 4,
            maxBarThickness: 48,
            barPercentage: 0.5,
            categoryPercentage: 0.5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});
</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>