<?php
class UserModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getAllUsers($keyword = '') {
        // Filter by status_aktif = 'Ya'
        $sql = "SELECT * FROM tb_user WHERE status_aktif = 'Ya'"; 
        $params = [];
        
        if (!empty($keyword)) {
            $sql .= " AND (nama LIKE ? OR email LIKE ?)";
            $keyword = "%$keyword%";
            $params = [$keyword, $keyword];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser($data) {
        $nama = $data['nama'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        
        $sql = "INSERT INTO tb_user (nama, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nama, $email, $password]);
    }

    public function deleteUser($id) {
        // Soft delete user by setting status_aktif to 'Tidak'
        $sql = "UPDATE tb_user SET status_aktif = 'Tidak' WHERE id_user = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function updateUser($id, $data) {
        if (!empty($data['password'])) {
            $sql = "UPDATE tb_user SET nama = ?, email = ?, password = ? WHERE id_user = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$data['nama'], $data['email'], $data['password'], $id]);
        } else {
            $sql = "UPDATE tb_user SET nama = ?, email = ? WHERE id_user = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$data['nama'], $data['email'], $id]);
        }
    }
}
