<?php
if (!isset($data_menunggu)) {
    header("Location: " . BASEURL . "/index.php?url=admin/pengajuan");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Peminjaman - Admin Perpustakaan</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
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
            <div class="admin-header">
                <div class="section-title">
                    <h2>⏳ Pengajuan Peminjaman</h2>
                </div>
                <div style="display: flex; gap: 16px; align-items: center;">
                    <form method="GET" action="index.php" style="display: flex; gap: 8px;">
                        <input type="hidden" name="url" value="admin/pengajuan">
                        <input type="text" name="search" placeholder="Cari peminjaman..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Cari</button>
                        <?php if(isset($_GET['search']) && trim($_GET['search']) !== ''): ?>
                            <a href="index.php?url=admin/pengajuan" class="btn" style="background: #f1f5f9; color: var(--text-main); padding: 8px 16px; text-decoration: none; border-radius: 6px;">Reset</a>
                        <?php endif; ?>
                    </form>
                    <button onclick="showModal('modal-tambah-peminjaman')" class="btn btn-primary">➕ Input Peminjaman</button>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Peminjam & Buku</th>
                            <th>Tgl Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data_menunggu)): ?>
                            <tr><td colspan="4" style="text-align: center;">Tidak ada pengajuan baru.</td></tr>
                        <?php else: foreach ($data_menunggu as $loan): ?>
                        <tr>
                            <td>
                                <strong><?= $loan['nama_siswa']; ?></strong><br>
                                <small><?= $loan['judul_buku']; ?></small>
                            </td>
                            <td><?= $loan['tanggal_pinjam'] ?: '-'; ?></td>
                            <td><span class="badge badge-menunggu"><?= $loan['STATUS']; ?></span></td>
                            <td>
                                <a href="index.php?url=admin/setujuPeminjaman&id=<?= $loan['id_peminjaman']; ?>" class="btn btn-sm btn-success">Setuju</a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="showTolakModal(<?= $loan['id_peminjaman']; ?>)">Tolak</button>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tolak Peminjaman Modal -->
    <div id="modal-tolak-peminjaman" class="modal">
        <div class="modal-card">
            <h3>❌ Tolak Peminjaman</h3>
            <form action="index.php?url=admin/tolakPeminjaman" method="POST">
                <input type="hidden" name="id" id="tolak-id">
                <div class="form-group">
                    <label>Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-family: inherit;" rows="4" placeholder="Masukkan alasan kenapa pengajuan ditolak..."></textarea>
                </div>
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-danger" style="flex: 1">Tolak Pengajuan</button>
                    <button type="button" onclick="closeModal('modal-tolak-peminjaman')" class="btn" style="background: #f1f5f9; color: var(--text-main)">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Input Peminjaman Modal -->
    <div id="modal-tambah-peminjaman" class="modal">
        <div class="modal-card">
            <h3>📖 Input Peminjaman Baru</h3>
            <form action="index.php?url=admin/tambahPeminjaman" method="POST">
                <div class="form-group">
                    <label>Pilih Anggota</label>
                    <select name="id_user" required style="width: 100%; padding: 10px; border-radius: 8px;">
                        <?php foreach($data_anggota as $u): ?>
                            <option value="<?= $u['id_user'] ?>"><?= $u['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pilih Buku</label>
                    <select name="id_buku" required style="width: 100%; padding: 10px; border-radius: 8px;">
                        <?php foreach($data_buku as $b): ?>
                            <?php if($b['stok'] > 0): ?>
                                <option value="<?= $b['id_buku'] ?>"><?= $b['judul_buku'] ?> (Stok: <?= $b['stok'] ?>)</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label>Deadline Kembali</label>
                    <input type="date" name="tanggal_kembali" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                </div>
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1">Simpan</button>
                    <button type="button" onclick="closeModal('modal-tambah-peminjaman')" class="btn" style="background: #f1f5f9; color: var(--text-main)">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showModal(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function showTolakModal(id) {
            document.getElementById('tolak-id').value = id;
            showModal('modal-tolak-peminjaman');
        }
    </script>
</body>
</html>
