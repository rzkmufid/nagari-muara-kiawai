<?php
session_start();


// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


require_once __DIR__ . '../../../models/User.php';


$userModel = new User();


// Cek apakah user adalah admin
if (!$userModel->isAdmin($_SESSION['user_id'])) {
    echo "Akses ditolak. Hanya admin yang dapat mengakses halaman ini.";
    exit();
}

$pageTitle = "Tambah User";
ob_start(); // Start output buffering
?>
    <div class="container-fluid mt-5">
        <h2>Tambah User Baru</h2>
        <form method="POST" action="proses_tambah_user.php">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select class="form-control" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="user1">User1</option>
                    <option value="user2">User2</option>
                </select>
            </div>
            <a href="user_management.php" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Tambah User</button>
        </form>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<?php
$content = ob_get_clean(); // Get buffered content
include __DIR__ . '/../layout.php'; // Include layout with content
?>