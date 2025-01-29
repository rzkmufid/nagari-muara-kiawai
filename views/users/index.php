<?php
function startSessionIfNotStarted() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}


// Panggil fungsi ini di awal file yang membutuhkan session
startSessionIfNotStarted();


require_once __DIR__ . '/../../models/User.php';


$userModel = new User();


// Cek apakah user adalah admin
if (!$userModel->isAdmin($_SESSION['user_id'])) {
    echo "Akses ditolak. Hanya admin yang dapat mengakses halaman ini.";
    exit();
}


// Proses delete jika ada parameter
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Pastikan tidak menghapus akun admin yang sedang login
    if ($id != $_SESSION['user_id']) {
        $userModel->deleteUser($id);
        $_SESSION['success_message'] = "Data berhasil dihapus.";
    } else {
        $_SESSION['error_message'] = "Tidak dapat menghapus akun sendiri.";
    }
    
    header("Location: index.php");
    exit();
}


// Ambil semua data user
$userList = $userModel->getAllUsers();


// Hitung statistik
$statistik = [
    'total_user' => count($userList),
    'total_admin' => count(array_filter($userList, function($user) {
        return $user['role'] == 'admin';
    })),
    'total_user' => count(array_filter($userList, function($user) {
        return $user['role'] == 'user';
    })),
    'total_waliNagari' => count(array_filter($userList, function($user) {
        return $user['role'] == 'wali_nagari';
    }))
];


$pageTitle = "Manajemen User";
ob_start(); // Start output buffering
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-users me-2"></i> Manajemen User
                    </h3>
                    <div class="btn-group" role="group">
                        <a href="tambah_user.php" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah User
                        </a>
                        <button id="cetakLaporan" class="btn btn-light btn-sm">
                            <i class="fas fa-print me-1"></i> Cetak Laporan
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Informasi Statistik User -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-users me-2 text-primary"></i>Total User
                                        </h5>
                                    </div>
                                    <p class="card-text display-6 text-primary mb-0">
                                        <?= $statistik['total_user'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-user-shield me-2 text-success"></i>Total Admin
                                        </h5>
                                    </div>
                                    <p class="card-text display-6 text-success mb-0">
                                        <?= $statistik['total_admin'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-user me-2 text-warning"></i>Total Wali Nagari
                                        </h5>
                                    </div>
                                    <p class="card-text display-6 text-warning mb-0">
                                        <?= $statistik['total_waliNagari'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Tampilkan pesan sukses atau error -->
                    <?php if(isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success_message'] ?>
                            <?php unset($_SESSION['success_message']); ?>
                        </div>
                    <?php endif; ?>


                    <?php if(isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error_message'] ?>
                            <?php unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>


                    <!-- Tabel User -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataUser">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <!-- <th>Terakhir Login</th> -->
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($userList as $user): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td>
                                        <?php 
                                        switch($user['role']) {
                                            case 'admin':
                                                echo '<span class="badge bg-success">Admin</span>';
                                                break;
                                            case 'wali_nagari':
                                                echo '<span class="badge bg-primary">Wali Nagari</span>';
                                                break;
                                            case 'user':
                                                echo '<span class="badge bg-primary">User</span>';
                                                break;
                                            default:
                                                echo '<span class="badge bg-secondary">Tidak Dikenal</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if($user['id'] != $_SESSION['user_id']): ?>
                                            <a href="?action=delete&id=<?= $user['id'] ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php endif; // Hanya tampilkan tombol hapus jika bukan user yang sedang login ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Cetak Laporan -->
<div class="modal fade" id="cetakModal" tabindex="-1" aria-labelledby="cetakModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cetakModalLabel">Cetak Laporan User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCetak">
                    <div class="mb-3">
                        <label class="form-label">Pilih Jenis Laporan</label>
                        <select class="form-select" name="jenis_laporan" disabled>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Filter User</label>
                        <select class="form-select" name="user">
                            <option value="">Semua User</option>
                            <?php foreach ($userList as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnCetak">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Di dalam <head> atau di akhir <body> layout.php -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi DataTable
    if ($.fn.DataTable) {
        $('#dataUser ').DataTable({
            "order": [[0, "asc"]]
        });
    }


    // Tombol Cetak Laporan
    var cetakLaporanBtn = document.getElementById('cetakLaporan');
    var cetakModal = document.getElementById('cetakModal');
    var btnCetak = document.getElementById('btnCetak');


    if (cetakLaporanBtn) {
        cetakLaporanBtn.addEventListener('click', function(e) {
            e.preventDefault();
            var modal = new bootstrap.Modal(cetakModal);
            modal.show();
        });
    }


    if (btnCetak) {
        btnCetak.addEventListener('click', function() {
            var form = document.getElementById('formCetak');
            var formData = new FormData(form);
            var url = 'cetak_laporan.php?' + new URLSearchParams(formData).toString();
            window.open(url, '_blank');


            // Tutup modal
            var modalInstance = bootstrap.Modal.getInstance(cetakModal);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    }
});
</script>


<?php
$content = ob_get_clean(); // Get buffered content
include '../../views/layout.php'; // Include layout with content