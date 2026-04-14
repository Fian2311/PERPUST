<?php
if (!isset($riwayat_peminjaman)) {
    header("Location: " . BASEURL . "/index.php?url=siswa/riwayat");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Baca - Perpustakaan Digital</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                <a href="index.php?url=siswa/pinjaman" class="nav-link">
                    <span>📖 Pinjaman Aktif</span>
                </a>
                <a href="index.php?url=siswa/riwayat" class="nav-link active">
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
                    <h2>📜 Riwayat Bacaan Anda</h2>
                </div>
                <form method="GET" action="index.php" style="display: flex; gap: 8px;">
                    <input type="hidden" name="url" value="siswa/riwayat">
                    <input type="text" name="search" placeholder="Cari buku..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                    <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Cari</button>
                    <?php if(isset($_GET['search']) && trim($_GET['search']) !== ''): ?>
                        <a href="index.php?url=siswa/riwayat" class="btn" style="background: #f1f5f9; color: var(--text-main); padding: 8px 16px; text-decoration: none; border-radius: 6px;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Buku yang Selesai Dibaca</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($riwayat_peminjaman)): ?>
                            <tr><td colspan="5" style="text-align:center; padding: 40px;">Belum ada riwayat aktivitas.</td></tr>
                        <?php else: $no = 1; foreach ($riwayat_peminjaman as $row): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <strong><?= $row['judul_buku']; ?></strong>
                                    <?php if($row['STATUS'] == 'Ditolak' && !empty($row['alasan_penolakan'])): ?>
                                    <div style="font-size: 12px; color: #ef4444; margin-top: 4px;"><strong>Alasan Ditolak:</strong> <?= htmlspecialchars($row['alasan_penolakan']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= $row['tanggal_pinjam']; ?></td>
                                <td><?= $row['tanggal_kembali']; ?></td>
                                <td>
                                    <?php 
                                        $badgeClass = 'badge-selesai';
                                        if($row['STATUS'] == 'Ditolak') $badgeClass = 'badge-ditolak';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $row['STATUS']; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
