<?php
require_once '../app/config/Database.php';

try {
    global $conn;
    
    echo "--- Table Structure for tb_peminjaman ---\n";
    $stmt = $conn->query("DESCRIBE tb_peminjaman");
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    
    echo "\n--- SQL Mode ---\n";
    $stmt = $conn->query("SELECT @@sql_mode");
    print_r($stmt->fetch(PDO::FETCH_ASSOC));
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
