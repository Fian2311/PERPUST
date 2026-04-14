<?php
// Prevent direct access to view
if (!isset($data_buku) || !isset($stats)) {
    header("Location: " . BASEURL . "/index.php?url=admin/dashboard");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan Digital</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Specific enhancements for Admin Dashboard */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .stat-icon {
            font-size: 24px;
            opacity: 0.8;
            margin-bottom: 8px;
            display: block;
        }

        .action-cell {
            display: flex;
            gap: 8px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
            animation: fadeIn 0.2s ease-out;
        }

        .modal-card {
            background: white;
            width: 100%;
            max-width: 450px;
            border-radius: 20px;
            padding: 32px;
            box-shadow: var(--shadow-lg);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-muted);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile-section">
                <div class="avatar">A</div>
                <h3>Admin Library</h3>
                <small><?= $_SESSION['user']['username'] ?></small>
            </div>
            
            <nav>
                <a href="index.php?url=admin/dashboard" class="nav-link <?= (!isset($_GET['url']) || strpos($_GET['url'], 'dashboard') !== false)  ? 'active' : '' ?>">
                    <span>🏠 Dashboard Overview</span>
                </a>
                <a href="index.php?url=admin/buku" class="nav-link <?= strpos($_GET['url'] ?? '', 'buku') !== false ? 'active' : '' ?>">
                    <span>📚 Kelola Koleksi Buku</span>
                </a>
                <a href="index.php?url=admin/anggota" class="nav-link <?= strpos($_GET['url'] ?? '', 'anggota') !== false ? 'active' : '' ?>">
                    <span>👥 Manajemen Anggota</span>
                </a>
                <a href="index.php?url=admin/pengajuan" class="nav-link <?= strpos($_GET['url'] ?? '', 'pengajuan') !== false ? 'active' : '' ?>" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>⏳ Pengajuan Pinjaman</span>
                    <?php if(isset($pendingCount) && $pendingCount > 0): ?>
                        <span style="width: 10px; height: 10px; background-color: #ef4444; border-radius: 50%; display: inline-block; box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);"></span>
                    <?php endif; ?>
                </a>
                <a href="index.php?url=admin/pinjamanAktif" class="nav-link <?= strpos($_GET['url'] ?? '', 'pinjamanAktif') !== false ? 'active' : '' ?>">
                    <span>📖 Peminjaman Aktif</span>
                </a>
                <a href="index.php?url=admin/riwayat" class="nav-link <?= strpos($_GET['url'] ?? '', 'riwayat') !== false ? 'active' : '' ?>">
                    <span>📜 Riwayat Peminjaman</span>
                </a>
                <a href="index.php?url=auth/logout" class="nav-link logout-btn">
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            
            <div id="tab-home" class="tab-content active">
                <div class="welcome-banner">
                    <h1>Selamat Datang, Admin!</h1>
                    <p>Pantau semua aktivitas perpustakaan dalam satu panel kontrol.</p>
                </div>

                <div class="stats-grid">
                    <div class="card">
                        <span class="stat-icon">📚</span>
                        <h3>Total Buku</h3>
                        <h1><?= $stats['total_buku']; ?></h1>
                    </div>
                    <div class="card">
                        <span class="stat-icon">👥</span>
                        <h3>Total Anggota</h3>
                        <h1><?= $stats['total_anggota']; ?></h1>
                    </div>
                    <div class="card" style="border-left: 4px solid var(--primary);">
                        <span class="stat-icon">📖</span>
                        <h3>Peminjaman Aktif</h3>
                        <h1><?= $stats['peminjaman_aktif']; ?></h1>
                    </div>
                </div>

                <div style="margin-top: 32px;">
                    <h2>Akses Cepat</h2>
                    <div class="stats-grid">
                        <a href="index.php?url=admin/buku" style="text-decoration: none; color: inherit;">
                            <div class="card" style="cursor: pointer; transition: transform 0.2s;">
                                <h3>Kelola Buku</h3>
                                <p>Tambah, edit, atau hapus koleksi buku.</p>
                            </div>
                        </a>
                        <a href="index.php?url=admin/anggota" style="text-decoration: none; color: inherit;">
                            <div class="card" style="cursor: pointer; transition: transform 0.2s;">
                                <h3>Manajemen Anggota</h3>
                                <p>Kelola data siswa dan anggota perpustakaan.</p>
                            </div>
                        </a>
                        <a href="index.php?url=admin/monitoring" style="text-decoration: none; color: inherit;">
                            <div class="card" style="cursor: pointer; transition: transform 0.2s;">
                                <h3>Monitoring Pinjaman</h3>
                                <p>Pantau status peminjaman dan pengembalian.</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }
    </style>
</body>
</html>
