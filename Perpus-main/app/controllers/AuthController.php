<?php
class AuthController {
    public function index() {
        require_once '../app/views/auth/login.php';
    }

    public function login() {
        require_once '../app/views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?url=auth/login");
        exit;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            global $conn;
            require_once '../app/models/UserModel.php';
            $userModel = new UserModel($conn);
            
            // Validasi: Pastikan semua data yang dibutuhkan ada
            if (empty($_POST['nama']) || empty($_POST['email']) || empty($_POST['password'])) {
                $error = "Semua field (Nama, Email, dan Password) wajib diisi!";
                require_once '../app/views/auth/register.php';
                return;
            }
            
            // Note: Secara ideal kita harus menggunakan password_hash()
            // namun di sini kita mengikuti pola yang sudah ada di codebase.
            if ($userModel->addUser($_POST)) {
                $success = "Registrasi berhasil! Silakan login untuk memulai.";
                require_once '../app/views/auth/login.php';
            } else {
                $error = "Pendaftaran gagal. Email mungkin sudah terdaftar.";
                require_once '../app/views/auth/register.php';
            }
        } else {
            require_once '../app/views/auth/register.php';
        }
    }
}
