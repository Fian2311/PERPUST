<?php
require_once __DIR__ . '/../models/BukuModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PeminjamanModel.php';

class AdminController {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            session_write_close();
            header("Location: index.php?url=auth/login");
            exit;
        }
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        global $conn;
        $bukuModel = new BukuModel($conn);
        $userModel = new UserModel($conn);
        $peminjamanModel = new PeminjamanModel($conn);

        $data_buku = $bukuModel->getAllBuku();
        $data_anggota = $userModel->getAllUsers();
        $data_peminjaman = $peminjamanModel->getAllPeminjaman();
        
        $pendingCount = $peminjamanModel->getPendingCount();

        // Calculate Stats
        $stats = [
            'total_buku' => count($data_buku),
            'total_anggota' => count($data_anggota),
            'peminjaman_aktif' => 0
        ];

        foreach($data_peminjaman as $p) {
            if($p['STATUS'] == 'Dipinjam') $stats['peminjaman_aktif']++;
        }

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function buku() {
        global $conn;
        $bukuModel = new BukuModel($conn);
        $peminjamanModel = new PeminjamanModel($conn);
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $data_buku = $bukuModel->getAllBuku($keyword);
        $pendingCount = $peminjamanModel->getPendingCount();
        require_once __DIR__ . '/../views/admin/buku.php';
    }

    public function anggota() {
        global $conn;
        $userModel = new UserModel($conn);
        $peminjamanModel = new PeminjamanModel($conn);
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $data_anggota = $userModel->getAllUsers($keyword);
        $pendingCount = $peminjamanModel->getPendingCount();
        require_once __DIR__ . '/../views/admin/anggota.php';
    }

    public function pengajuan() {
        global $conn;
        $peminjamanModel = new PeminjamanModel($conn);
        $bukuModel = new BukuModel($conn);
        $userModel = new UserModel($conn);
        
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $data_peminjaman = $peminjamanModel->getAllPeminjaman($keyword);
        
        $data_menunggu = array_filter($data_peminjaman, function($r) { return $r['STATUS'] == 'Menunggu Persetujuan'; });
        $pendingCount = $peminjamanModel->getPendingCount();

        $data_buku = $bukuModel->getAllBuku();
        $data_anggota = $userModel->getAllUsers();
        require_once __DIR__ . '/../views/admin/pengajuan.php';
    }

    public function pinjamanAktif() {
        global $conn;
        $peminjamanModel = new PeminjamanModel($conn);
        $bukuModel = new BukuModel($conn);
        $userModel = new UserModel($conn);
        
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $data_peminjaman = $peminjamanModel->getAllPeminjaman($keyword);
        
        $data_dipinjam = array_filter($data_peminjaman, function($r) { return $r['STATUS'] == 'Dipinjam'; });
        $pendingCount = $peminjamanModel->getPendingCount();

        $data_buku = $bukuModel->getAllBuku();
        $data_anggota = $userModel->getAllUsers();
        require_once __DIR__ . '/../views/admin/pinjaman_aktif.php';
    }

    public function riwayat() {
        global $conn;
        $peminjamanModel = new PeminjamanModel($conn);
        $bukuModel = new BukuModel($conn);
        $userModel = new UserModel($conn);
        
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $data_peminjaman = $peminjamanModel->getAllPeminjaman($keyword);
        
        $data_riwayat  = array_filter($data_peminjaman, function($r) { return $r['STATUS'] == 'Dikembalikan' || $r['STATUS'] == 'Ditolak'; });
        $pendingCount = $peminjamanModel->getPendingCount();

        require_once __DIR__ . '/../views/admin/riwayat.php';
    }

    // --- Buku Operations ---
    public function tambahBuku() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            global $conn;
            
            $currentYear = date('Y');
            if ($_POST['tahun'] > $currentYear) {
                echo "<script>alert('Tahun terbit tidak boleh melebihi tahun sekarang.'); window.location.href='index.php?url=admin/buku';</script>";
                exit;
            }
            
            if ($_POST['stok'] <= 0) {
                echo "<script>alert('Stok buku tidak boleh 0 atau kurang.'); window.location.href='index.php?url=admin/buku';</script>";
                exit;
            }

            $model = new BukuModel($conn);
            if ($model->addBuku($_POST)) {
                session_write_close();
                header("Location: index.php?url=admin/buku");
                exit;
            }
        }
    }

    public function hapusBuku() {
        if (isset($_GET['id'])) {
            global $conn;
            
            $stmt = $conn->prepare("SELECT COUNT(*) FROM tb_peminjaman WHERE id_buku = ? AND STATUS IN ('Dipinjam', 'Menunggu Persetujuan')");
            $stmt->execute([$_GET['id']]);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                echo "<script>alert('Buku tidak bisa dihapus karena masih ada yang meminjam atau dalam pengajuan.'); window.location.href='index.php?url=admin/buku';</script>";
                exit;
            }

            $model = new BukuModel($conn);
            $model->deleteBuku($_GET['id']);
            session_write_close();
            header("Location: index.php?url=admin/buku");
            exit;
        }
    }

    public function editBuku() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
            global $conn;
            $model = new BukuModel($conn);
            if ($model->updateBuku($_GET['id'], $_POST)) {
                session_write_close();
                header("Location: index.php?url=admin/buku");
                exit;
            }
        }
    }

    // --- Anggota Operations ---
    public function tambahAnggota() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            global $conn;
            $model = new UserModel($conn);
            // Default password '123' for simplicity or from input
            $_POST['password'] = $_POST['password'] ?? '123';
            if ($model->addUser($_POST)) {
                session_write_close();
                header("Location: index.php?url=admin/anggota");
                exit;
            }
        }
    }

    public function editAnggota() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
            global $conn;
            $model = new UserModel($conn);
            if ($model->updateUser($_GET['id'], $_POST)) {
                session_write_close();
                header("Location: index.php?url=admin/anggota");
                exit;
            }
        }
    }

    public function hapusAnggota() {
        if (isset($_GET['id'])) {
            global $conn;
            
            $stmt = $conn->prepare("SELECT COUNT(*) FROM tb_peminjaman WHERE id_user = ? AND STATUS IN ('Dipinjam', 'Menunggu Persetujuan')");
            $stmt->execute([$_GET['id']]);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                echo "<script>alert('Anggota tidak bisa dihapus karena masih meminjam buku atau memiliki pengajuan.'); window.location.href='index.php?url=admin/anggota';</script>";
                exit;
            }

            $model = new UserModel($conn);
            $model->deleteUser($_GET['id']);
            session_write_close();
            header("Location: index.php?url=admin/anggota");
            exit;
        }
    }

    // --- Peminjaman Operations ---
    public function tambahPeminjaman() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            global $conn;
            $pModel = new PeminjamanModel($conn);
            $bModel = new BukuModel($conn);
            
            $_POST['STATUS'] = 'Dipinjam'; // Admin adds = auto approved

            // Insert Loan
            if ($pModel->addPeminjaman($_POST)) {
                // Decrease Stock
                $bModel->kurangiStok($_POST['id_buku']);
                session_write_close();
                header("Location: index.php?url=admin/pinjamanAktif");
                exit;
            }
        }
    }

    public function setujuPeminjaman() {
        if (isset($_GET['id'])) {
            global $conn;
            $pModel = new PeminjamanModel($conn);
            $bModel = new BukuModel($conn);

            // Get loan info
            $loan = $pModel->getPeminjamanById($_GET['id']);

            // Check stock
            $buku = $bModel->getBukuById($loan['id_buku']);
            if ($buku['stok'] > 0) {
                // Update status & set current date as tanggal_pinjam
                $pModel->startLoan($_GET['id']);
                // Decrease stock
                $bModel->kurangiStok($loan['id_buku']);
            }
            
            session_write_close();
            header("Location: index.php?url=admin/pengajuan");
            exit;
        }
    }

    public function tolakPeminjaman() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            global $conn;
            $pModel = new PeminjamanModel($conn);
            $alasan = isset($_POST['alasan_penolakan']) ? trim($_POST['alasan_penolakan']) : '';
            $pModel->updateStatus($_POST['id'], 'Ditolak', $alasan);
            session_write_close();
            header("Location: index.php?url=admin/pengajuan");
            exit;
        } elseif (isset($_GET['id'])) {
            global $conn;
            $pModel = new PeminjamanModel($conn);
            $pModel->updateStatus($_GET['id'], 'Ditolak');
            session_write_close();
            header("Location: index.php?url=admin/pengajuan");
            exit;
        }
    }

    public function kembalikanBuku() {
        if (isset($_GET['id'])) {
            global $conn;
            $model = new PeminjamanModel($conn);
            $bukuModel = new BukuModel($conn);

            // Get loan info to find book ID
            $loan = $model->getPeminjamanById($_GET['id']);
            
            if ($loan && $loan['STATUS'] == 'Dipinjam') {
                // Update status & set current date as tanggal_kembali
                $model->finishLoan($_GET['id']);
                // Increase stock
                $bukuModel->tambahStok($loan['id_buku']);
            }
            
            session_write_close();
            header("Location: index.php?url=admin/pinjamanAktif");
            exit;
        }
    }
}