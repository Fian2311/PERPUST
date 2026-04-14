<?php
require_once __DIR__ . '/../models/PeminjamanModel.php';

class PeminjamanController {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            session_write_close();
            header("Location: index.php?url=auth/login");
            exit;
        }
    }

    public function index() {
        global $conn;
        $model = new PeminjamanModel($conn);
        $peminjaman = $model->getPeminjamanBySiswa($_SESSION['user']['id_user']);
        
        require_once __DIR__ . '/../views/peminjaman/index.php';
    }

    public function requestPinjam() {
        if (isset($_GET['id'])) {
            global $conn;
            $model = new PeminjamanModel($conn);
            
            $data = [
                'id_user' => $_SESSION['user']['id_user'],
                'id_buku' => $_GET['id'],
                'STATUS' => 'Menunggu Persetujuan'
            ];

            if ($model->addPeminjaman($data)) {
                $_SESSION['flash_success'] = "Buku berhasil diajukan untuk pinjaman! Silakan tunggu konfirmasi Admin.";
                session_write_close();
                header("Location: index.php?url=siswa/pinjaman");
                exit;
            }
        }
        session_write_close();
        header("Location: index.php?url=siswa/jelajah");
        exit;
    }
}
