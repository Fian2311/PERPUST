<?php
require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/BukuModel.php';

global $conn;

$userModel = new UserModel($conn);
$bukuModel = new BukuModel($conn);

echo "Testing Soft Delete Verification...\n";

// 1. Get initial counts
$initialUsers = count($userModel->getAllUsers());
$initialBuku = count($bukuModel->getAllBuku());

echo "Initial active users: $initialUsers\n";
echo "Initial active books: $initialBuku\n";

// 2. Add a test user
$testUserData = ['nama' => 'Test Soft Delete', 'email' => 'test_soft@example.com', 'password' => '123'];
$userModel->addUser($testUserData);
$stmt = $conn->prepare("SELECT id_user FROM tb_user WHERE email = ?");
$stmt->execute([$testUserData['email']]);
$testUserId = $stmt->fetchColumn();

echo "Added test user with ID: $testUserId\n";

// 3. Delete the test user (soft delete)
$userModel->deleteUser($testUserId);
echo "Performed soft delete on user $testUserId.\n";

// 4. Verify user is gone from getAllUsers but exists in DB
$currentUsers = count($userModel->getAllUsers());
$stmt = $conn->prepare("SELECT status_aktif FROM tb_user WHERE id_user = ?");
$stmt->execute([$testUserId]);
$status = $stmt->fetchColumn();

echo "Current active users: $currentUsers (expected same as initial: $initialUsers)\n";
echo "User status in database: $status (expected: Tidak)\n";

if ($currentUsers == $initialUsers && $status == 'Tidak') {
    echo "User Soft Delete SUCCESS!\n";
} else {
    echo "User Soft Delete FAILED!\n";
}

// 5. Clean up test user
$conn->exec("DELETE FROM tb_user WHERE id_user = $testUserId");
echo "Cleaned up test user.\n";

echo "\nVerification complete.\n";
?>
