<?php
if (!isset($data_buku)) {
    header("Location: " . BASEURL . "/index.php?url=siswa/jelajah");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajah Buku - Perpustakaan Digital</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .book-cover {
            background: linear-gradient(135deg, white, #e2e8f0);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
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
                <a href="index.php?url=siswa/jelajah" class="nav-link active">
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div class="section-title" style="margin-bottom: 0;">
                    <h2>📚 Katalog Buku Terbaru</h2>
                </div>
                <form method="GET" action="index.php" style="display: flex; gap: 8px;">
                    <input type="hidden" name="url" value="siswa/jelajah">
                    <input type="text" name="search" placeholder="Cari buku..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Cari</button>
                    <?php if(isset($_GET['search']) && trim($_GET['search']) !== ''): ?>
                        <a href="index.php?url=siswa/jelajah" class="btn" style="background: #f1f5f9; color: var(--text-main); padding: 8px 16px; text-decoration: none; border-radius: 6px;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="book-grid">
                <?php if (empty($data_buku)): ?>
                    <div class="card" style="grid-column: 1/-1; text-align: center;">
                        <p>Maaf, koleksi buku belum tersedia saat ini.</p>
                    </div>
                <?php else: foreach ($data_buku as $buku): ?>
                    <?php 
                        $isAvailable = $buku['stok'] > 1; // Minimum 1 for borrow? logic from previous code
                        $isAvailable = $buku['stok'] > 0;
                    ?>
                    <div class="book-card <?= $isAvailable ? 'available' : 'unavailable' ?>">
                        <div class="book-cover">
                            <span class="book-icon" style="font-size: 40px;">📘</span>
                        </div>
                        
                        <h4><?= $buku['judul_buku']; ?></h4>
                        <p><strong><?= $buku['pengarang']; ?></strong></p>
                        <p><small style="color: var(--text-muted)"><?= $buku['penerbit']; ?> (<?= $buku['tahun_terbit']; ?>)</small></p>
                        
                        <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center;">
                            <span class="status-label <?= $isAvailable ? 'tersedia' : 'dipinjam' ?>">
                                <?= $isAvailable ? 'Tersedia: ' . $buku['stok'] : 'Stok Habis' ?>
                            </span>
                            
                            <?php if($isAvailable): ?>
                                <a href="index.php?url=peminjaman/requestPinjam&id=<?= $buku['id_buku'] ?>" class="btn btn-primary btn-sm">Pinjam</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
