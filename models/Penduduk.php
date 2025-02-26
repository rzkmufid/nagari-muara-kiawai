<?php
require_once __DIR__ . '/../config/database.php';


class Penduduk
{
    private $conn;
    private $table_name = 'penduduk';


    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    // Create
    public function create($data) {
        // Validasi NIK unik
        if ($this->nikSudahAda($data['nik'])) {
            return false;
        }
    
        // Validasi panjang NIK
        if (strlen($data['nik']) > 16) {
            return false;
        }
    
        $query = "INSERT INTO " . $this->table_name . " 
                  (nama, nik, jenis_kelamin, tempat_lahir, tanggal_lahir, pekerjaan, jorong) 
                  VALUES (:nama, :nik, :jenis_kelamin, :tempat_lahir, :tanggal_lahir, :pekerjaan, :jorong)";
    
        try {
            $stmt = $this->conn->prepare($query);
    
            // Sanitasi data
            $nama = htmlspecialchars(strip_tags($data['nama']));
            $nik = htmlspecialchars(strip_tags($data['nik']));
            $jenis_kelamin = htmlspecialchars(strip_tags($data['jenis_kelamin']));
            $tempat_lahir = htmlspecialchars(strip_tags($data['tempat_lahir']));
            $tanggal_lahir = htmlspecialchars(strip_tags($data['tanggal_lahir']));
            $pekerjaan = htmlspecialchars(strip_tags($data['pekerjaan']));
            $jorong = htmlspecialchars(strip_tags($data['jorong']));
    
            // Bind parameter
            $stmt->bindParam(":nama", $nama);
            $stmt->bindParam(":nik", $nik);
            $stmt->bindParam(":jenis_kelamin", $jenis_kelamin);
            $stmt->bindParam(":tempat_lahir", $tempat_lahir);
            $stmt->bindParam(":tanggal_lahir", $tanggal_lahir);
            $stmt->bindParam(":pekerjaan", $pekerjaan);
            $stmt->bindParam(":jorong", $jorong);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error untuk debugging
            error_log("Error creating penduduk: " . $e->getMessage());
            return false;
        }
    }

    public function createHistory($data) {
        // Validasi NIK unik
    
        $query = "INSERT INTO history 
                  (id, nama, nik, jenis_kelamin, tempat_lahir, tanggal_lahir, pekerjaan, jorong) 
                  VALUES (:id, :nama, :nik, :jenis_kelamin, :tempat_lahir, :tanggal_lahir, :pekerjaan, :jorong)";
    
    
        try {
            $stmt = $this->conn->prepare($query);
    
    
            // Sanitasi data
            $id = htmlspecialchars(strip_tags($data['id']));
            $nama = htmlspecialchars(strip_tags($data['nama']));
            $nik = htmlspecialchars(strip_tags($data['nik']));
            $jenis_kelamin = htmlspecialchars(strip_tags($data['jenis_kelamin']));
            $tempat_lahir = htmlspecialchars(strip_tags($data['tempat_lahir']));
            $tanggal_lahir = htmlspecialchars(strip_tags($data['tanggal_lahir']));
            $pekerjaan = htmlspecialchars(strip_tags($data['pekerjaan']));
            $jorong = htmlspecialchars(strip_tags($data['jorong']));
    
    
            // Bind parameter
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":nama", $nama);
            $stmt->bindParam(":nik", $nik);
            $stmt->bindParam(":jenis_kelamin", $jenis_kelamin);
            $stmt->bindParam(":tempat_lahir", $tempat_lahir);
            $stmt->bindParam(":tanggal_lahir", $tanggal_lahir);
            $stmt->bindParam(":pekerjaan", $pekerjaan);
            $stmt->bindParam(":jorong", $jorong);
    
    
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error untuk debugging
            error_log("Error creating penduduk: " . $e->getMessage());
            return false;
        }
    }
    
    
    // Method tambahan untuk memeriksa NIK yang sudah ada
    public function nikSudahAda($nik) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE nik = :nik";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nik', $nik);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


    // Read All
    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readHistory()
    {
        $query = "SELECT * FROM history ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $getData = $this->readOne($id);
        $filtered = array_diff_key($getData, array_flip(['id', 'created_at']));

        if ($filtered !== $data) {
            $this->createHistory($filtered);
        }


        $query = "UPDATE " . $this->table_name . " 
                  SET nama=:nama, nik=:nik, jenis_kelamin=:jenis_kelamin, 
                      tempat_lahir=:tempat_lahir, tanggal_lahir=:tanggal_lahir, pekerjaan=:pekerjaan, jorong=:jorong 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);


        // Sanitasi data
        $nama = htmlspecialchars(strip_tags($data['nama']));
        $nik = htmlspecialchars(strip_tags($data['nik']));
        $jenis_kelamin = htmlspecialchars(strip_tags($data['jenis_kelamin']));
        $tempat_lahir = htmlspecialchars(strip_tags($data['tempat_lahir']));
        $tanggal_lahir = htmlspecialchars(strip_tags($data['tanggal_lahir']));
        $pekerjaan = htmlspecialchars(strip_tags($data['pekerjaan']));
        $jorong = htmlspecialchars(strip_tags($data['jorong']));


        // Bind parameter
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nama", $nama);
        $stmt->bindParam(":nik", $nik);
        $stmt->bindParam(":jenis_kelamin", $jenis_kelamin);
        $stmt->bindParam(":tempat_lahir", $tempat_lahir);
        $stmt->bindParam(":tanggal_lahir", $tanggal_lahir);
        $stmt->bindParam(":pekerjaan", $pekerjaan);
        $stmt->bindParam(":jorong", $jorong);


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
    // Mendapatkan penduduk berdasarkan jenis kelamin
    public function getPendudukByJenisKelamin()
    {
        $query = "SELECT jenis_kelamin, COUNT(*) as jumlah 
              FROM " . $this->table_name . " 
              GROUP BY jenis_kelamin";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];
        foreach ($result as $row) {
            $data[$row['jenis_kelamin']] = $row['jumlah'];
        }

        return $data;
    }


    // Mendapatkan penduduk all
    public function getPendudukByPekerjaanAll()
    {
        $query = "SELECT pekerjaan, COUNT(*) as jumlah 
              FROM " . $this->table_name . " 
              GROUP BY pekerjaan 
              ORDER BY jumlah DESC 
              LIMIT 10";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];
        foreach ($result as $row) {
            $data[$row['pekerjaan']] = $row['jumlah'];
        }

        return $data;
    }

    // Mendapatkan data penduduk berdasarkan pekerjaan
    public function getPendudukByPekerjaan($pekerjaan)
    {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE pekerjaan = :pekerjaan
                  ORDER BY nama DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pekerjaan', $pekerjaan);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mendapatkan data penduduk berdasarkan umur
    public function getPendudukByUmur($golongan_umur)
    {
        if ($golongan_umur == "Diatas 74") {
            $query = "SELECT * FROM " . $this->table_name . " 
                      WHERE YEAR(CURDATE()) - YEAR(tanggal_lahir) > 74
                      ORDER BY tempat_lahir DESC";
        } else {
            list($age_min, $age_max) = explode("-", $golongan_umur);
            $query = "SELECT * FROM " . $this->table_name . " 
                      WHERE YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN :age_min AND :age_max 
                      ORDER BY tempat_lahir DESC";
        }

        $stmt = $this->conn->prepare($query);
        if (isset($age_min) && isset($age_max)) {
            $stmt->bindParam(':age_min', $age_min, PDO::PARAM_INT);
            $stmt->bindParam(':age_max', $age_max, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPendudukStatistikPekerjaan()
    {
        $query = "SELECT 
                    pekerjaan, 
                    COUNT(*) as jumlah,
                    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM " . $this->table_name . "), 2) as persentase
                  FROM " . $this->table_name . "
                  GROUP BY pekerjaan
                  ORDER BY jumlah DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Metode untuk membaca penduduk dengan filter
    public function readAllWithFilter($filters = [])
    {
        // Query dasar
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1 ";

        // Array untuk binding parameter
        $params = [];


        // Filter berdasarkan tanggal_lahir
        if (isset($filters['tanggal_lahir_min']) && $filters['tanggal_lahir_min'] !== '') {
            $query .= " AND tanggal_lahir >= :tanggal_lahir_min";
            $params[':tanggal_lahir_min'] = $filters['tanggal_lahir_min'];
        }

        if (isset($filters['tanggal_lahir_max']) && $filters['tanggal_lahir_max'] !== '') {
            $query .= " AND tanggal_lahir <= :tanggal_lahir_max";
            $params[':tanggal_lahir_max'] = $filters['tanggal_lahir_max'];
        }


        // Filter berdasarkan tempat_lahir
        if (isset($filters['tempat_lahir']) && $filters['tempat_lahir'] !== '') {
            $query .= " AND tempat_lahir LIKE :tempat_lahir";
            $params[':tempat_lahir'] = '%' . $filters['tempat_lahir'] . '%';
        }


        // Filter berdasarkan pekerjaan
        if (isset($filters['pekerjaan']) && $filters['pekerjaan'] !== '') {
            $query .= " AND pekerjaan = :pekerjaan";
            $params[':pekerjaan'] = $filters['pekerjaan'];
        }


        // Tambahkan pengurutan
        $query .= " ORDER BY id ASC";


        // Siapkan statement
        $stmt = $this->conn->prepare($query);


        // Bind parameter
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }


        // Eksekusi query
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Metode untuk mendapatkan daftar pekerjaan unik
    public function getUniquePekerjaan()
    {
        $query = "SELECT DISTINCT pekerjaan FROM " . $this->table_name . " ORDER BY pekerjaan ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    // Metode untuk mendapatkan rentang umur
    public function getUmurStatistik()
    {
        return [
            ['min' => 0, 'max' => 5, 'label' => '0-5 tahun'],
            ['min' => 6, 'max' => 12, 'label' => '6-12 tahun'],
            ['min' => 13, 'max' => 18, 'label' => '13-18 tahun'],
            ['min' => 19, 'max' => 35, 'label' => '19-35 tahun'],
            ['min' => 36, 'max' => 55, 'label' => '36-55 tahun'],
            ['min' => 56, 'max' => 100, 'label' => '56 tahun ke atas']
        ];
    }
    // Metode untuk mendapatkan statistik penduduk berdasarkan umur dan jenis kelamin
    public function getStatistikUmurJenisKelamin()
    {
        $query = "SELECT 
        CASE 
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 0 AND 4 THEN '00-04'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 5 AND 9 THEN '05-09'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 10 AND 14 THEN '10-14'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 15 AND 19 THEN '15-19'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 20 AND 24 THEN '20-24'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 25 AND 29 THEN '25-29'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 30 AND 34 THEN '30-34'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 35 AND 39 THEN '35-39'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 40 AND 44 THEN '40-44'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 45 AND 49 THEN '45-49'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 50 AND 54 THEN '50-54'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 55 AND 59 THEN '55-59'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 60 AND 64 THEN '60-64'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 65 AND 69 THEN '65-69'
            WHEN YEAR(CURDATE()) - YEAR(tanggal_lahir) BETWEEN 70 AND 74 THEN '70-74'
            ELSE 'Diatas 74'
        END AS golongan_umur,
        SUM(CASE WHEN jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END) AS laki_laki,
        SUM(CASE WHEN jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) AS perempuan
    FROM " . $this->table_name . "
    GROUP BY golongan_umur
    ORDER BY 
        CASE golongan_umur
            WHEN '00-04' THEN 1
            WHEN '05-09' THEN 2
            WHEN '10-14' THEN 3
            WHEN '15-19' THEN 4
            WHEN '20-24' THEN 5
            WHEN '25-29' THEN 6
            WHEN '30-34' THEN 7
            WHEN '35-39' THEN 8
            WHEN '40-44' THEN 9
            WHEN '45-49' THEN 10
            WHEN '50-54' THEN 11
            WHEN '55-59' THEN 12
            WHEN '60-64' THEN 13
            WHEN '65-69' THEN 14
            WHEN '70-74' THEN 15
            ELSE 16
        END";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
