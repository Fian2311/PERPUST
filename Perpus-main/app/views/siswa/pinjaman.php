<?php
if (!isset($data_pinjam_aktif)) {
    header("Location: " . BASEURL . "/index.php?url=siswa/pinjaman");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjaman Aktif - Perpustakaan Digital</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .loan-card {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 16px;
        }
        .loan-info {
            flex: 1;
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
                <a href="index.php?url=siswa/dashboard" class="nav-link">
                    <span>🏠 Dashboard Overview</span>
                </a>
                <a href="index.php?url=siswa/jelajah" class="nav-link">
                    <span>📚 Jelajah Buku</span>
                </a>
                <a href="index.php?url=siswa/pinjaman" class="nav-link active">
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
            <?php if(isset($_SESSION['flash_success'])): ?>
                <div style="background: rgba(34, 197, 94, 0.1); color: #4ade80; padding: 16px; border-radius: 12px; margin-bottom: 24px; border: 1px solid rgba(34, 197, 94, 0.2); display: flex; align-items: center; gap: 12px; animation: fadeIn 0.4s ease-out;">
                    <span style="font-size: 20px;">✅</span>
                    <strong style="flex:1;"><?= $_SESSION['flash_success'] ?></strong>
                    <button onclick="this.parentElement.style.display='none'" style="background:none; border:none; color:#4ade80; cursor:pointer;" aria-label="Close">✖</button>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div class="section-title" style="margin-bottom: 0;">
                    <h2>📖 Peminjaman Sedang Berjalan</h2>
                </div>
                <form method="GET" action="index.php" style="display: flex; gap: 8px;">
                    <input type="hidden" name="url" value="siswa/pinjaman">
                    <input type="text" name="search" placeholder="Cari buku..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Cari</button>
                    <?php if(isset($_GET['search']) && trim($_GET['search']) !== ''): ?>
                        <a href="index.php?url=siswa/pinjaman" class="btn" style="background: #f1f5f9; color: var(--text-main); padding: 8px 16px; text-decoration: none; border-radius: 6px;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="stats-grid" style="grid-template-columns: 1fr;">
                <?php if (empty($data_pinjam_aktif)): ?>
                    <div class="card" style="text-align: center; padding: 48px;">
                        <span style="font-size: 48px; display: block; margin-bottom: 16px;">📚</span>
                        <p>Anda belum memiliki peminjaman aktif.</p>
                        <a href="index.php?url=siswa/jelajah" class="btn btn-primary" style="margin-top: 20px; text-decoration: none; display: inline-block;">Cari Buku Sekarang</a>
                    </div>
                <?php else: foreach ($data_pinjam_aktif as $row): ?>
                    <div class="card loan-card">
                        <span style="font-size: 32px;">📘</span>
                        <div class="loan-info">
                            <h3><?= $row['judul_buku']; ?></h3>
                            <p>Dipinjam: <?= $row['tanggal_pinjam']; ?> &bull; Kembali: <strong><?= $row['tanggal_kembali'] ?: 'Menunggu Konfirmasi'; ?></strong></p>
                        </div>
                        <div class="loan-status">
                            <?php 
                                $badgeClass = 'badge-selesai';
                                if($row['STATUS'] == 'Dipinjam') $badgeClass = 'badge-dipinjam';
                                elseif($row['STATUS'] == 'Menunggu Persetujuan') $badgeClass = 'badge-menunggu';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $row['STATUS']; ?></span>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
