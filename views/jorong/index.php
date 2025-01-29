<?php
require_once __DIR__ . '/../../models/Jorong.php';


// Inisialisasi model
$jorongModel = new Jorong();
function startSessionIfNotStarted() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
startSessionIfNotStarted();


// Proses delete jika ada parameter
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $jorongModel->delete($id);
    header("Location: index.php");
    exit();
}


// Ambil semua data jorong
$jorongList = $jorongModel->readAll();


// Ambil statistik
$statistik = $jorongModel->getStatistik();


$pageTitle = "Data Jorong";
ob_start(); // Start output buffering
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i> Data Jorong
                    </h3>
                    <div class="btn-group" role="group">
                        <?php
                            if ($_SESSION['user_role'] !== 'wali_nagari') {
                        ?>
                        <a href="tambah.php" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah Jorong
                        </a>
                        <?php } ?>
                        <button id="cetakLaporan" class="btn btn-light btn-sm">
                            <i class="fas fa-print me-1"></i> Cetak Laporan
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Informasi Statistik Jorong -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-map-pin me-2 text-primary"></i>Total Jorong
                                        </h5>
                                    </div>
                                    <p class="card-text display-6 text-primary mb-0">
                                        <?= $statistik['total_jorong'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-home me-2 text-success"></i>Total KK
                                        </h5>
                                    </div>
                                    <p class="card-text display-6 text-success mb-0">
                                        <?= $statistik['total_kk'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-expand-arrows-alt me-2 text-warning"></i>Total Luas
                                        </h5>
                                    </div>
                                    <p class="card-text display-6 text-warning mb-0">
                                        <?= $statistik['total_luas'] ?> Ha
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Tabel Jorong -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataJorong">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Jorong</th>
                                    <th>Kepala Jorong</th>
                                    <th>Luas Wilayah (Ha)</th>
                                    <th>Jumlah KK</th>
                                    <?php
                                        if ($_SESSION['user_role'] !== 'wali_nagari') {
                                    ?>
                                    <th class="text-center">Aksi</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($jorongList as $jorong): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $jorong['nama_jorong'] ?></td>
                                    <td><?= $jorong['kepala_jorong'] ?></td>
                                    <td><?= $jorong['luas_wilayah'] ?></td>
                                    <td><?= $jorong['jumlah_kk'] ?></td>
                                    <?php
                                        if ($_SESSION['user_role'] !== 'wali_nagari') {
                                    ?>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="edit.php?id=<?= $jorong['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?action=delete&id=<?= $jorong['id'] ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Yakin ingin menghapus data?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <?php } ?>
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
                <h5 class="modal-title" id="cetakModalLabel">Cetak Laporan Jorong</h5>
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
    <label class="form-label">Filter Jorong</label>
    <select class="form-select" name="jorong">
        <option value="">Semua Jorong</option>
        <?php foreach ($jorongList as $jorong): ?>
            <option value="<?= $jorong['id'] ?>"><?= $jorong['nama_jorong'] ?></option>
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
        $('#dataJorong').DataTable({
            "order": [[0, "asc"]]
        });
    }


    // Tambahkan event listener dengan vanilla JavaScript
    var cetakLaporanBtn = document.getElementById('cetakLaporan');
    var cetakModal = document.getElementById('cetakModal');
    var btnCetak = document.getElementById('btnCetak');


    if (cetakLaporanBtn) {
        cetakLaporanBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Tombol cetak diklik');
            
            // Gunakan metode Bootstrap untuk menampilkan modal
            if (bootstrap && bootstrap.Modal) {
                var modal = new bootstrap.Modal(cetakModal);
                modal.show();
            } else {
                console.error('Bootstrap Modal tidak ditemukan');
            }
        });
    }


    if (btnCetak) {
        btnCetak.addEventListener('click', function() {
            var form = document.getElementById('formCetak');
            var formData = new FormData(form);
            var url = 'cetak_laporan.php?' + new URLSearchParams(formData).toString();


            window.open(url, '_blank');


            // Tutup modal
            if (bootstrap && bootstrap.Modal) {
                var modalInstance = bootstrap.Modal.getInstance(cetakModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        });
    }
});
$('#btnCetak').on('click', function() {
    const formData = $('#formCetak').serialize();
    console.log('Form Data:', formData); // Logging data yang dikirim
    const url = 'cetak_laporan.php?' + formData;


    console.log('Cetak URL:', url); // Logging URL


    // Redirect ke URL cetak
    window.open(url, '_blank');
    
    // Tutup modal menggunakan metode Bootstrap
    var cetakModal = bootstrap.Modal.getInstance(document.getElementById('cetakModal'));
    cetakModal.hide();
});
</script>


<?php
$content = ob_get_clean(); // Get buffered content
include '../../views/layout.php'; // Include layout with content