<?php
require_once __DIR__ . '/../config/database.php';


class Jorong
{
    private $conn;
    private $table_name = 'jorong';


    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    // Create
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (nama_jorong, kepala_jorong, luas_wilayah, jumlah_kk, keterangan) 
                  VALUES (:nama_jorong, :kepala_jorong, :luas_wilayah, :jumlah_kk, :keterangan)";

        $stmt = $this->conn->prepare($query);


        // Sanitasi data
        $nama_jorong = htmlspecialchars(strip_tags($data['nama_jorong']));
        $kepala_jorong = htmlspecialchars(strip_tags($data['kepala_jorong']));
        $luas_wilayah = floatval($data['luas_wilayah']);
        $jumlah_kk = intval($data['jumlah_kk']);
        $keterangan = htmlspecialchars(strip_tags($data['keterangan'] ?? ''));


        // Bind parameter
        $stmt->bindParam(":nama_jorong", $nama_jorong);
        $stmt->bindParam(":kepala_jorong", $kepala_jorong);
        $stmt->bindParam(":luas_wilayah", $luas_wilayah);
        $stmt->bindParam(":jumlah_kk", $jumlah_kk);
        $stmt->bindParam(":keterangan", $keterangan);


        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    // Read All
    public function readAll($orderBy = 'id', $orderDirection = 'ASC')
    {
        $allowedColumns = ['id', 'nama_jorong', 'jumlah_kk', 'luas_wilayah'];
        $orderBy = in_array($orderBy, $allowedColumns) ? $orderBy : 'id';
        $orderDirection = in_array(strtoupper($orderDirection), ['ASC', 'DESC']) ? strtoupper($orderDirection) : 'ASC';


        $query = "SELECT * FROM " . $this->table_name . " ORDER BY $orderBy $orderDirection";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambahkan method baru di dalam class Jorong
    public function getJorongById($id) {
        // Jika $id kosong atau null, kembalikan semua data
        if (empty($id)) {
            return $this->readAll();
        }
    
    
        // Query untuk mengambil data jorong berdasarkan ID
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Ambil semua data (bisa lebih dari satu)
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Jika tidak ada data, kembalikan array kosong
            return $result ? $result : [];
        } catch (PDOException $e) {
            // Tangani error
            error_log("Error in getJorongById: " . $e->getMessage());
            return [];
        }
    }


    // Read Single
    public function readOne($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Update
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET nama_jorong=:nama_jorong, kepala_jorong=:kepala_jorong, 
                      luas_wilayah=:luas_wilayah, jumlah_kk=:jumlah_kk, 
                      keterangan=:keterangan 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);


        // Sanitasi data
        $nama_jorong = htmlspecialchars(strip_tags($data['nama_jorong']));
        $kepala_jorong = htmlspecialchars(strip_tags($data['kepala_jorong']));
        $luas_wilayah = floatval($data['luas_wilayah']);
        $jumlah_kk = intval($data['jumlah_kk']);
        $keterangan = htmlspecialchars(strip_tags($data['keterangan'] ?? ''));


        // Bind parameter
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nama_jorong", $nama_jorong);
        $stmt->bindParam(":kepala_jorong", $kepala_jorong);
        $stmt->bindParam(":luas_wilayah", $luas_wilayah);
        $stmt->bindParam(":jumlah_kk", $jumlah_kk);
        $stmt->bindParam(":keterangan", $keterangan);


        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    // Delete
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);


        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    // Statistik Jorong
    public function getStatistik()
    {
        $query = "SELECT 
                    COUNT(*) as total_jorong, 
                    SUM(jumlah_kk) as total_kk, 
                    SUM(luas_wilayah) as total_luas
                  FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
