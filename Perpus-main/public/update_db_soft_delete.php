<?php
require_once __DIR__ . '/../app/config/Database.php';

try {
    global $conn;
    
    echo "Updating database schema for soft delete...\n";
    
    // Add status_aktif to tb_user
    $sql1 = "ALTER TABLE tb_user ADD COLUMN status_aktif ENUM('Ya', 'Tidak') NOT NULL DEFAULT 'Ya'";
    $conn->exec($sql1);
    echo "Added 'status_aktif' to 'tb_user'.\n";
    
    // Add status_tampil to tb_buku
    $sql2 = "ALTER TABLE tb_buku ADD COLUMN status_tampil ENUM('Ya', 'Tidak') NOT NULL DEFAULT 'Ya'";
    $conn->exec($sql2);
    echo "Added 'status_tampil' to 'tb_buku'.\n";
    
    echo "Successfully updated database schema.\n";
    
} catch(PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Columns already exist.\n";
    } else {
        echo "Error updating database: " . $e->getMessage() . "\n";
    }
}
?>
