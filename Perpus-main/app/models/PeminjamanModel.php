<?php
class PeminjamanModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getPeminjamanBySiswa($id_user, $keyword = '') {
        // Menggabungkan tabel peminjaman dan buku agar muncul judulnya
        $sql = "SELECT p.*, b.judul_buku 
                FROM tb_peminjaman p 
                JOIN tb_buku b ON p.id_buku = b.id_buku 
                WHERE p.id_user = ?";
        $params = [$id_user];
        
        if (!empty($keyword)) {
            $sql .= " AND b.judul_buku LIKE ?";
            $params[] = "%$keyword%";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingCount() {
        $sql = "SELECT COUNT(*) as total 
                FROM tb_peminjaman p 
                JOIN tb_buku b ON p.id_buku = b.id_buku 
                JOIN tb_user u ON p.id_user = u.id_user 
                WHERE p.STATUS = 'Menunggu Persetujuan'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }

    public function getAllPeminjaman($keyword = '') {
        $sql = "SELECT p.*, b.judul_buku, u.nama AS nama_siswa 
                FROM tb_peminjaman p 
                JOIN tb_buku b ON p.id_buku = b.id_buku 
                JOIN tb_user u ON p.id_user = u.id_user";
        
        $params = [];
        if (!empty($keyword)) {
            $sql .= " WHERE b.judul_buku LIKE ? OR u.nama LIKE ?";
            $keyword = "%$keyword%";
            $params = [$keyword, $keyword];
        }
        
        $sql .= " ORDER BY p.tanggal_pinjam DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id_peminjaman, $status, $alasan = null) {
        if ($status == 'Ditolak' && $alasan !== null) {
            $sql = "UPDATE tb_peminjaman SET STATUS = ?, alasan_penolakan = ?, tanggal_kembali = CURRENT_DATE WHERE id_peminjaman = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $alasan, $id_peminjaman]);
        } else {
            $sql = "UPDATE tb_peminjaman SET STATUS = ? WHERE id_peminjaman = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $id_peminjaman]);
        }
    }

    public function addPeminjaman($data) {
        $status = isset($data['STATUS']) ? $data['STATUS'] : 'Menunggu Persetujuan';
        $tanggal_pinjam = ($status == 'Dipinjam') ? date('Y-m-d') : null;
        $sql = "INSERT INTO tb_peminjaman (id_user, id_buku, tanggal_pinjam, tanggal_kembali, STATUS) VALUES (?, ?, ?, NULL, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['id_user'], $data['id_buku'], $tanggal_pinjam, $status]);
    }

    public function startLoan($id) {
        $sql = "UPDATE tb_peminjaman SET STATUS = 'Dipinjam', tanggal_pinjam = CURRENT_DATE WHERE id_peminjaman = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function finishLoan($id) {
        $sql = "UPDATE tb_peminjaman SET STATUS = 'Dikembalikan', tanggal_kembali = CURRENT_DATE WHERE id_peminjaman = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function deletePeminjamanByBuku($id_buku) {
        $sql = "DELETE FROM tb_peminjaman WHERE id_buku = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_buku]);
    }

    public function deletePeminjamanByUser($id_user) {
        $sql = "DELETE FROM tb_peminjaman WHERE id_user = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_user]);
    }

    public function getPeminjamanById($id) {
        $sql = "SELECT * FROM tb_peminjaman WHERE id_peminjaman = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}