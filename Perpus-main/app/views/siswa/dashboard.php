<?php
if (!isset($stats)) {
    header("Location: " . BASEURL . "/index.php?url=siswa/dashboard");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Perpustakaan Digital</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }
        .stat-icon {
            font-size: 24px;
            opacity: 0.8;
            margin-bottom: 8px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile-section">
                <div class="avatar"><?= strtoupper(substr($_SESSION['user']['nama'], 0, 1)) ?></div>
                <h3><?= $_SESSION['user']['nama'] ?></h3>
                <small>Siswa / Member</small>
            </div>
            
            <nav>
                <a href="index.php?url=siswa/dashboard" class="nav-link active">
                    <span>🏠 Dashboard Overview</span>
                </a>
                <a href="index.php?url=siswa/jelajah" class="nav-link">
                    <span>📚 Jelajah Buku</span>
                </a>
                <a href="index.php?url=siswa/pinjaman" class="nav-link">
                    <span>📖 Pinjaman Aktif</span>
                </a>
                <a href="index.php?url=siswa/riwayat" class="nav-link">
                    <span>📜 Riwayat Baca</span>
                </a>
                <a href="index.php?url=auth/logout" class="nav-link logout-btn" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-banner">
                <h1>Hai, <?= explode(' ', $_SESSION['user']['nama'])[0]; ?>! 👋</h1>
                <p>Pantau aktivitas membaca kamu di sini.</p>
            </div>

            <div class="stats-grid">
                <div class="card">
                    <span class="stat-icon">📖</span>
                    <h3>Sedang Dipinjam</h3>
                    <h1><?= $stats['sedang_dipinjam']; ?></h1>
                </div>
                <div class="card">
                    <span class="stat-icon">⏳</span>
                    <h3>Menunggu Persetujuan</h3>
                    <h1><?= $stats['menunggu']; ?></h1>
                </div>
                <div class="card" style="border-left: 4px solid var(--primary);">
                    <span class="stat-icon">✅</span>
                    <h3>Total Buku Dibaca</h3>
                    <h1><?= $stats['total_dibaca']; ?></h1>
                </div>
            </div>

            <div style="margin-top: 32px;">
                <h2>Akses Cepat</h2>
                <div class="stats-grid">
                    <a href="index.php?url=siswa/jelajah" style="text-decoration: none; color: inherit;">
                        <div class="card" style="cursor: pointer; transition: transform 0.2s;">
                            <h3>Jelajah Katalog</h3>
                            <p>Cari dan pinjam buku favoritmu hari ini.</p>
                        </div>
                    </a>
                    <a href="index.php?url=siswa/pinjaman" style="text-decoration: none; color: inherit;">
                        <div class="card" style="cursor: pointer; transition: transform 0.2s;">
                            <h3>Cek Pinjaman</h3>
                            <p>Lihat status buku yang sedang kamu pinjam.</p>
                        </div>
                    </a>
                    <a href="index.php?url=siswa/riwayat" style="text-decoration: none; color: inherit;">
                        <div class="card" style="cursor: pointer; transition: transform 0.2s;">
                            <h3>Riwayat Baca</h3>
                            <p>Lihat koleksi buku yang sudah pernah kamu baca.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
