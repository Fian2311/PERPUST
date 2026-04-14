<?php
class BukuModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getAllBuku($keyword = '') {
        // Filter by status_tampil = 'Ya'
        $sql = "SELECT * FROM tb_buku WHERE status_tampil = 'Ya'";
        $params = [];
        
        if (!empty($keyword)) {
            $sql .= " AND (judul_buku LIKE ? OR pengarang LIKE ? OR penerbit LIKE ?)";
            $keyword = "%$keyword%";
            $params = [$keyword, $keyword, $keyword];
        }
        
        $sql .= " ORDER BY judul_buku ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addBuku($data) {
        // Default stok 1 jika tidak diisi
        $stok = isset($data['stok']) ? $data['stok'] : 1; 
        $sql = "INSERT INTO tb_buku (judul_buku, pengarang, penerbit, tahun_terbit, stok) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['judul'], $data['penulis'], $data['penerbit'], $data['tahun'], $stok]);
    }

    public function deleteBuku($id) {
        // Soft delete book by setting status_tampil to 'Tidak'
        $sql = "UPDATE tb_buku SET status_tampil = 'Tidak' WHERE id_buku = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function updateBuku($id, $data) {
        // Default stok ke 1 jika tidak diset, atau ambil stok lama (idealnya)
        // Di sini kita asumsikan form kirim semua data
        $sql = "UPDATE tb_buku SET judul_buku = ?, pengarang = ?, penerbit = ?, tahun_terbit = ?, stok = ? WHERE id_buku = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['judul'], $data['penulis'], $data['penerbit'], $data['tahun'], $data['stok'], $id]);
    }

    public function kurangiStok($id_buku) {
        $sql = "UPDATE tb_buku SET stok = stok - 1 WHERE id_buku = ? AND stok > 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_buku]);
    }

    public function tambahStok($id_buku) {
        $sql = "UPDATE tb_buku SET stok = stok + 1 WHERE id_buku = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_buku]);
    }
    public function getBukuById($id) {
        $sql = "SELECT * FROM tb_buku WHERE id_buku = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
