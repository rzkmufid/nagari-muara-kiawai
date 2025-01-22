<?php
session_start();


// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}


require_once __DIR__ . '../../../models/User.php';


$userModel = new User();


// Cek apakah user adalah admin
if (!$userModel->isAdmin($_SESSION['user_id'])) {
    echo "Akses ditolak. Hanya admin yang dapat mengakses halaman ini.";
    exit();
}


// Cek apakah ID user disediakan
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}


$user_id = $_GET['id'];
$user = $userModel->getUserById($user_id);


// Jika user tidak ditemukan
if (!$user) {
    header("Location: index.php");
    exit();
}

$pageTitle = "Edit User";
ob_start(); // Start output buffering
?>

    <div class="container-fluid mt-5">
        <h2>Edit User</h2>
        
        <?php 
        // Tampilkan pesan error jika ada
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>


        <form method="POST" action="proses_edit_user.php">
            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
            
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" 
                       value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" class="form-control" name="password">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select class="form-control" name="role" required>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="user1" <?= $user['role'] == 'user1' ? 'selected' : '' ?>>User1</option>
                    <option value="user2" <?= $user['role'] == 'user2' ? 'selected' : '' ?>>User2</option>
                </select>
            </div>
            
            <a href="index.php" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<?php
$content = ob_get_clean(); // Get buffered content
include '../../views/layout.php'; // Include layout with content