<?php
require_once 'config/auth.php';
require_once 'config/database.php';
cekLogin();

$pageTitle  = 'Dashboard';
$activeMenu = 'dashboard';

// Total pelanggan
$totalPelanggan = $koneksi->query("SELECT COUNT(*) c FROM pelanggan")->fetch()['c'];

// Pelanggan baru bulan ini
$pelangganBaru = $koneksi->query("
    SELECT COUNT(*) c FROM pelanggan
    WHERE MONTH(tanggal_daftar) = MONTH(CURDATE()) AND YEAR(tanggal_daftar) = YEAR(CURDATE())
")->fetch()['c'];

// Total transaksi
$totalTransaksi = $koneksi->query("SELECT COUNT(*) c FROM transaksi")->fetch()['c'];

// Pelanggan aktif
$pelangganAktif = $koneksi->query("SELECT COUNT(*) c FROM pelanggan WHERE status = 'Aktif'")->fetch()['c'];

// Data pelanggan terbaru (5)
$pelangganTerbaru = $koneksi->query("
    SELECT * FROM pelanggan ORDER BY tanggal_daftar DESC LIMIT 5
")->fetchAll();

// Grafik pelanggan baru per bulan (6 bulan, berakhir di bulan pelanggan terbaru)
$maxTgl = $koneksi->query("SELECT MAX(tanggal_daftar) m FROM pelanggan")->fetch()['m'];
$acuan  = $maxTgl ? strtotime($maxTgl) : time();
$mulai  = date('Y-m-01', strtotime('-5 months', $acuan));

$stmt = $koneksi->prepare("
    SELECT DATE_FORMAT(tanggal_daftar, '%Y-%m') periode, COUNT(*) jml
    FROM pelanggan
    WHERE tanggal_daftar >= ?
    GROUP BY periode
");
$stmt->execute([$mulai]);
$grafikRaw = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$namaBulan   = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
$labelGrafik = [];
$dataGrafik  = [];
for ($i = 5; $i >= 0; $i--) {
    $ts  = strtotime("-$i month", $acuan);
    $key = date('Y-m', $ts);
    $labelGrafik[] = $namaBulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    $dataGrafik[]  = (int)($grafikRaw[$key] ?? 0);
}
include 'includes/header.php';
?>

<h4 class="fw-bold mb-1">Dashboard</h4>
<p class="text-muted mb-4">Selamat datang di Sistem Informasi Pengelolaan Data Pelanggan</p>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-value"><?= $totalPelanggan ?></div>
                <div class="stat-label">Total Pelanggan</div>
                <div class="stat-sub">Total data pelanggan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-person-plus"></i></div>
            <div>
                <div class="stat-value"><?= $pelangganBaru ?></div>
                <div class="stat-label">Pelanggan Baru (Bulan ini)</div>
                <div class="stat-sub">Dibanding bulan lalu</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-receipt"></i></div>
            <div>
                <div class="stat-value"><?= $totalTransaksi ?></div>
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-sub">Dari semua pelanggan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-person-check"></i></div>
            <div>
                <div class="stat-value"><?= $pelangganAktif ?></div>
                <div class="stat-label">Pelanggan Aktif</div>
                <div class="stat-sub">Masih bertransaksi</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card-box">
            <h6 class="fw-bold mb-3"><i class="bi bi-people me-1"></i> Data Pelanggan Terbaru</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr class="text-muted">
                            <th>No</th><th>Nama Pelanggan</th><th>No. HP</th><th>Tanggal Daftar</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($pelangganTerbaru as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($p['nama_pelanggan']) ?></td>
                            <td><?= htmlspecialchars($p['no_hp']) ?></td>
                            <td><?= date('d M Y', strtotime($p['tanggal_daftar'])) ?></td>
                            <td><span class="badge-aktif"><?= htmlspecialchars($p['status']) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($pelangganTerbaru)): ?>
                        <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card-box">
            <h6 class="fw-bold mb-3">Grafik Pelanggan</h6>
            <canvas id="grafikPelanggan" height="260"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
Chart.register(ChartDataLabels);

new Chart(document.getElementById('grafikPelanggan'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labelGrafik) ?>,
        datasets: [{
            label: 'Pelanggan Baru',
            data: <?= json_encode($dataGrafik) ?>,
            backgroundColor: '#6a9f3e',
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, position: 'top', labels: { boxWidth: 12 } },
            datalabels: {
                anchor: 'center',
                align: 'center',
                color: '#c0392b',
                font: { weight: 'bold' }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                title: { display: true, text: 'Jumlah Pelanggan' },
                grid: { borderDash: [4, 4] }
            },
            x: {
                title: { display: true, text: 'Bulan' },
                grid: { borderDash: [4, 4] }
            }
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>