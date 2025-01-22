<?php
require_once __DIR__ . '/../config/database.php';


class Sarana {
    private $conn;
    private $table_name = 'sarana_prasarana';


    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    // Create
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (jenis, jumlah, kondisi, keterangan) 
                  VALUES (:jenis, :jumlah, :kondisi, :keterangan)";
        
        $stmt = $this->conn->prepare($query);


        // Sanitasi data
        $jenis = htmlspecialchars(strip_tags($data['jenis']));
        $jumlah = intval($data['jumlah']);
        $kondisi = htmlspecialchars(strip_tags($data['kondisi']));
        $keterangan = htmlspecialchars(strip_tags($data['keterangan'] ?? ''));


        // Bind parameter
        $stmt->bindParam(":jenis", $jenis);
        $stmt->bindParam(":jumlah", $jumlah);
        $stmt->bindParam(":kondisi", $kondisi);
        $stmt->bindParam(":keterangan", $keterangan);


        if($stmt->execute()) {
            return true;
        }
        return false;
    }


    // Read All
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Read Single
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Update
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET jenis=:jenis, jumlah=:jumlah, 
                      kondisi=:kondisi, keterangan=:keterangan 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);


        // Sanitasi data
        $jenis = htmlspecialchars(strip_tags($data['jenis']));
        $jumlah = intval($data['jumlah']);
        $kondisi = htmlspecialchars(strip_tags($data['kondisi']));
        $keterangan = htmlspecialchars(strip_tags($data['keterangan'] ?? ''));


        // Bind parameter
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":jenis", $jenis);
        $stmt->bindParam(":jumlah", $jumlah);
        $stmt->bindParam(":kondisi", $kondisi);
        $stmt->bindParam(":keterangan", $keterangan);


        if($stmt->execute()) {
            return true;
        }
        return false;
    }


    // Delete
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);


        if($stmt->execute()) {
            return true;
        }
        return false;
    }


    // Metode untuk mendapatkan statistik sarana
    public function getSaranaByKondisi() {
        $query = "SELECT kondisi, COUNT(*) as jumlah 
                  FROM " . $this->table_name . " 
                  GROUP BY kondisi";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [];
        foreach ($result as $row) {
            $data[$row['kondisi']] = $row['jumlah'];
        }
        
        return $data;
    }


    // Metode untuk mendapatkan statistik jenis sarana
    public function getSaranaByJenis() {
        $query = "SELECT jenis, COUNT(*) as jumlah 
                  FROM " . $this->table_name . " 
                  GROUP BY jenis 
                  ORDER BY jumlah DESC 
                  LIMIT 10";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [];
        foreach ($result as $row) {
            $data[$row['jenis']] = $row['jumlah'];
        }
        
        return $data;
    }
}