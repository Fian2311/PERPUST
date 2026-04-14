<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "perpustakaan";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(['path' => '/', 'samesite' => 'Lax']);
    session_start();
}

// Global connection variable
global $conn;

// Define Base URL for assets (CSS, JS, etc)
$baseUrl = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
define('BASEURL', $baseUrl);