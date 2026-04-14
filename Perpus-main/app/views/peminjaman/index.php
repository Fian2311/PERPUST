<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Peminjaman - Perpustakaan</title>
    <link rel="stylesheet" href="<?= BASEURL ?>/css/style.css">
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .badge { padding: 5px 10px; border-radius: 4px; color: white; font-size: 12px; }
        .bg-warning { background-color: #f39c12; }
        .bg-success { background-color: #27ae60; }
        .bg-danger { background-color: #c0392b; }
        .bg-info { background-color: #2980b9; }
        .btn { padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Riwayat Peminjaman Saya</h2>
            <div>
                  <a href="index.php?url=siswa/dashboard" class="btn">Pinjam Buku Baru</a>
                 <a href="index.php?url=auth/logout" class="btn" style="background-color: #c0392b;">Logout</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($peminjaman)): ?>
                    <tr><td colspan="5" style="text-align: center;">Belum ada riwayat peminjaman.</td></tr>
                <?php else: ?>
                    <?php $no = 1; foreach($peminjaman as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $p['judul_buku'] ?></td>
                        <td><?= $p['tanggal_pinjam'] ?></td>
                        <td><?= $p['tanggal_kembali'] ?></td>
                        <td>
                            <?php 
                                $statusClass = 'bg-info';
                                if($p['STATUS'] == 'Menunggu Persetujuan') $statusClass = 'bg-warning';
                                elseif($p['STATUS'] == 'Dipinjam') $statusClass = 'bg-success';
                                elseif($p['STATUS'] == 'Ditolak') $statusClass = 'bg-danger';
                                elseif($p['STATUS'] == 'Dikembalikan') $statusClass = 'bg-info';
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= $p['STATUS'] ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
