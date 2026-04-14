<?php
require_once 'app/config/Database.php';
global $conn;

$tables = ['tb_buku', 'tb_user', 'tb_peminjaman', 'tb_admin'];
foreach($tables as $table) {
    echo "--- $table ---\n";
    $stmt = $conn->query("SHOW COLUMNS FROM $table");
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($cols as $col) {
        echo $col['Field'] . " | " . $col['Null'] . " | " . $col['Default'] . "\n";
    }
}

echo "\n--- Foreign Keys ---\n";
$stmt = $conn->query("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = (SELECT DATABASE()) AND REFERENCED_TABLE_NAME IS NOT NULL");
$fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($fks as $fk) {
    echo "{$fk['TABLE_NAME']}.{$fk['COLUMN_NAME']} -> {$fk['REFERENCED_TABLE_NAME']}.{$fk['REFERENCED_COLUMN_NAME']}\n";
}
