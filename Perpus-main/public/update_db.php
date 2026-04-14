<?php
require_once '../app/config/Database.php';

try {
    global $conn;
    
    echo "Updating database schema...\n";
    
    // Change STATUS from ENUM to VARCHAR to allow all statuses
    // Using VARCHAR(50) is safe and flexible
    $sql = "ALTER TABLE tb_peminjaman MODIFY COLUMN STATUS VARCHAR(50) NOT NULL DEFAULT 'Menunggu Persetujuan'";
    
    $conn->exec($sql);
    
    echo "Successfully updated 'tb_peminjaman' table. STATUS column is now VARCHAR(50).\n";
    
} catch(PDOException $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
?>
