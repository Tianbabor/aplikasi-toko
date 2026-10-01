<div class="sidebar">
    <div class="brand">
        <i class="bi bi-shop fs-5"></i>
        <span>TokoKu</span>
    </div>
    <nav class="nav flex-column py-2">
        <a href="dashboard.php" class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="data_pelanggan.php" class="nav-link <?= $activeMenu === 'pelanggan' ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Data Pelanggan
        </a>
        <a href="laporan.php" class="nav-link <?= $activeMenu === 'laporan' ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-text"></i> Laporan
        </a>
    </nav>
    <div class="logout-box">
        <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
    </div>
</div>
