<?php
if (!isset($data_anggota)) {
    header("Location: " . BASEURL . "/index.php?url=admin/anggota");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Anggota - Admin Perpustakaan</title>
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
                    <h2>👥 Manajemen Anggota</h2>
                </div>
                <div style="display: flex; gap: 16px; align-items: center;">
                    <form method="GET" action="index.php" style="display: flex; gap: 8px;">
                        <input type="hidden" name="url" value="admin/anggota">
                        <input type="text" name="search" placeholder="Cari anggota..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Cari</button>
                        <?php if(isset($_GET['search']) && trim($_GET['search']) !== ''): ?>
                            <a href="index.php?url=admin/anggota" class="btn" style="background: #f1f5f9; color: var(--text-main); padding: 8px 16px; text-decoration: none; border-radius: 6px;">Reset</a>
                        <?php endif; ?>
                    </form>
                    <button onclick="showModal('modal-tambah-anggota')" class="btn btn-primary">➕ Tambah Anggota</button>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($data_anggota as $user): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= $user['nama']; ?></strong></td>
                            <td><?= $user['email']; ?></td>
                            <td>
                                <div class="action-cell">
                                    <button onclick="editAnggota(<?= htmlspecialchars(json_encode($user)) ?>)" class="btn btn-sm btn-success">Edit</button>
                                    <a href="index.php?url=admin/hapusAnggota&id=<?= $user['id_user']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus anggota ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div id="modal-tambah-anggota" class="modal">
        <div class="modal-card">
            <h3>👥 Tambah Anggota Baru</h3>
            <form action="index.php?url=admin/tambahAnggota" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Defaut: 123">
                </div>
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1">Simpan</button>
                    <button type="button" onclick="closeModal('modal-tambah-anggota')" class="btn" style="background: #f1f5f9; color: var(--text-main)">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div id="modal-edit-anggota" class="modal">
        <div class="modal-card">
            <h3>📝 Edit Data Anggota</h3>
            <form id="form-edit-anggota" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" id="edit-nama" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="edit-email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin merubah">
                </div>
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1">Update</button>
                    <button type="button" onclick="closeModal('modal-edit-anggota')" class="btn" style="background: #f1f5f9; color: var(--text-main)">Batal</button>
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

        function editAnggota(data) {
            document.getElementById('edit-nama').value = data.nama;
            document.getElementById('edit-email').value = data.email;
            
            document.getElementById('form-edit-anggota').action = 'index.php?url=admin/editAnggota&id=' + data.id_user;
            showModal('modal-edit-anggota');
        }
    </script>
</body>
</html>
