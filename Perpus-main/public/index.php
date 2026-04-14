<?php
// Pastikan tidak ada output sebelum session_start
if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(['path' => '/', 'samesite' => 'Lax']);
    session_start();
}

require_once __DIR__ . '/../app/config/Database.php';

// Autoload Controllers
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/SiswaController.php';
require_once __DIR__ . '/../app/controllers/PeminjamanController.php';

// Routing
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'auth/index';
$url = explode('/', $url);

$controllerName = ucfirst($url[0]) . 'Controller'; 
$methodName = isset($url[1]) ? $url[1] : 'index'; 

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $methodName)) {
        $controller->$methodName();
    } else {
        echo "Error: Method '$methodName' tidak ditemukan di '$controllerName'";
    }
} else {
    // Jika tidak ditemukan, default ke login
    $auth = new AuthController();
    $auth->index();
}