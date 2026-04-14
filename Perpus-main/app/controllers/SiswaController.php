<?php
require_once __DIR__ . '/../models/PeminjamanModel.php';
require_once __DIR__ . '/../models/BukuModel.php';

class SiswaController {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
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
        $model = new PeminjamanModel($conn);
        $id_user = $_SESSION['user']['id_user'];
        $all_data = $model->getPeminjamanBySiswa($id_user);
        
        $stats = [
            'sedang_dipinjam' => 0,
            'menunggu' => 0,
            'total_dibaca' => 0
        ];

        foreach ($all_data as $row) {
            if ($row['STATUS'] == 'Dipinjam') $stats['sedang_dipinjam']++;
            if ($row['STATUS'] == 'Menunggu Persetujuan') $stats['menunggu']++;
            if ($row['STATUS'] == 'Dikembalikan') $stats['total_dibaca']++;
        }

        require_once __DIR__ . '/../views/siswa/dashboard.php';
    }

    public function jelajah() {
        global $conn;
        $bukuModel = new BukuModel($conn);
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $data_buku = $bukuModel->getAllBuku($keyword);
        require_once __DIR__ . '/../views/siswa/jelajah.php';
    }

    public function pinjaman() {
        global $conn;
        $model = new PeminjamanModel($conn);
        $id_user = $_SESSION['user']['id_user'];
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $all_data = $model->getPeminjamanBySiswa($id_user, $keyword);
        
        $data_pinjam_aktif = array_filter($all_data, function($row) {
            return $row['STATUS'] == 'Dipinjam' || $row['STATUS'] == 'Menunggu Persetujuan';
        });

        require_once __DIR__ . '/../views/siswa/pinjaman.php';
    }

    public function riwayat() {
        global $conn;
        $model = new PeminjamanModel($conn);
        $id_user = $_SESSION['user']['id_user'];
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $all_data = $model->getPeminjamanBySiswa($id_user, $keyword);
        
        $riwayat_peminjaman = array_filter($all_data, function($row) {
            return $row['STATUS'] == 'Dikembalikan' || $row['STATUS'] == 'Ditolak';
        });

        require_once __DIR__ . '/../views/siswa/riwayat.php';
    }
}