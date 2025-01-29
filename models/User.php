<?php
require_once __DIR__ . '/../config/database.php';


class User
{
    private $conn;
    private $table_name = 'users';


    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    public function register($username, $password, $role)
    {
        $query = "INSERT INTO " . $this->table_name . " (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }


    public function login($username, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Tambahkan method untuk mengecek apakah user adalah admin
    public function isAdmin($user_id)
    {
        $query = "SELECT role FROM " . $this->table_name . " WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($user && $user['role'] === 'admin');
    }

    public function deleteUser($user_id)
    {
        try {
            // Cek apakah user adalah admin
            $userModel = new User();
            if (!$userModel->isAdmin($_SESSION['user_id'])) {
                echo "Akses ditolak. Hanya admin yang dapat mengakses halaman ini.";
                exit();
            }

            // Jika user_id adalah admin, maka tidak bisa menghapus akun sendiri
            if ($_SESSION['user_id'] == $user_id) {
                return false;
            }

            // Mulai transaksi
            $this->conn->beginTransaction();

            // Cek apakah user dengan ID tersebut ada
            $check_query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE id = :user_id";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $check_stmt->execute();

            // Jika user tidak ditemukan
            if ($check_stmt->fetchColumn() == 0) {
                return false;
            }

            // Query untuk menghapus user
            $delete_query = "DELETE FROM " . $this->table_name . " WHERE id = :user_id";
            $delete_stmt = $this->conn->prepare($delete_query);
            $delete_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            // Eksekusi query
            $result = $delete_stmt->execute();

            // Commit transaksi
            $this->conn->commit();

            return $result;
        } catch (PDOException $e) {
            // Rollback transaksi jika terjadi error
            $this->conn->rollBack();

            // Log error (sebaiknya gunakan log system yang sesuai)
            error_log("Error deleting user: " . $e->getMessage());

            return false;
        }
    }




    // Tambahan method untuk mengecek apakah user bisa dihapus
    public function canDeleteUser($current_user_id, $user_to_delete_id)
    {
        // Cek apakah user yang akan dihapus adalah user yang sedang login
        if ($current_user_id == $user_to_delete_id) {
            return false;
        }


        // Cek apakah user yang akan dihapus ada di database
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_to_delete_id, PDO::PARAM_INT);
        $stmt->execute();


        // Jika user tidak ditemukan
        if ($stmt->fetchColumn() == 0) {
            return false;
        }


        return true;
    }

    // Metode untuk mendapatkan user berdasarkan ID
    public function getUserById($user_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Metode untuk update user
    public function updateUser($user_id, $username, $password = null, $role = null)
    {
        // Jika password tidak diubah, gunakan query tanpa password
        if ($password === null) {
            $query = "UPDATE " . $this->table_name . " 
                  SET username = :username, role = :role 
                  WHERE id = :user_id";
        } else {
            // Jika password diubah, sertakan password dalam update
            $query = "UPDATE " . $this->table_name . " 
                  SET username = :username, 
                      password = :password, 
                      role = :role 
                  WHERE id = :user_id";
        }


        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);


        // Bind password hanya jika password diubah
        if ($password !== null) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $hashed_password);
        }


        return $stmt->execute();
    }
}
