<?php
require_once __DIR__ . '/../../models/Penduduk.php';

function startSessionIfNotStarted() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
startSessionIfNotStarted();

// Inisialisasi model
$pendudukModel = new Penduduk();


// Ambil daftar pekerjaan unik
$pekerjaanList = $pendudukModel->getUniquePekerjaan();


// Ambil rentang umur
$umurRentang = $pendudukModel->getUmurStatistik();


// Proses filter
$filters = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['tanggal_lahir_min']) && $_GET['tanggal_lahir_min'] !== '') {
        $filters['tanggal_lahir_min'] = $_GET['tanggal_lahir_min'];
    }

    if (isset($_GET['tanggal_lahir_max']) && $_GET['tanggal_lahir_max'] !== '') {
        $filters['tanggal_lahir_max'] = $_GET['tanggal_lahir_max'];
    }

    if (isset($_GET['pekerjaan']) && $_GET['pekerjaan'] !== '') {
        $filters['pekerjaan'] = $_GET['pekerjaan'];
    }
}


// Proses delete jika ada parameter
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $pendudukModel->delete($id);
    header("Location: index.php");
    exit();
}


// Ambil data penduduk dengan filter
$pendudukList = empty($filters) ? $pendudukModel->readAll() : $pendudukModel->readAllWithFilter($filters);


$pageTitle = "Data Penduduk";
ob_start(); // Start output buffering
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <!-- Tambahkan tombol cetak di card header -->
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div class="spacer me-4 d-flex align-items-center">
                        <i class="fas fa-users me-2 mr-4"></i>
                        <h3 class="mb-0">Data Penduduk 2025</h3>
                    </div>
                    <div class="btn-group" role="group">
                        <?php
                            if ($_SESSION['user_role'] !== 'wali_nagari') {
                        ?>
                        <a href="tambah.php" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah Penduduk
                        </a>
                        <?php } ?>
                        <button id="cetakLaporan" class="btn btn-light btn-sm">
                            <i class="fas fa-print me-1"></i> Cetak Laporan
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Form Filter -->
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <i class="fas fa-filter me-2"></i> Filter Data Penduduk
                        </div>
                        <div class="card-body">
                            <form method="GET">
                                <div class="row g-3">
                                <div class="col-md-4">
                                        <label class="form-label">Tanggal Lahir Min</label>
                                        <input type="date" name="tanggal_lahir_min" class="form-control" value="<?= isset($_GET['tanggal_lahir_min']) ? $_GET['tanggal_lahir_min'] : '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal Lahir Maks</label>
                                        <input type="date" name="tanggal_lahir_max" class="form-control" value="<?= isset($_GET['tanggal_lahir_max']) ? $_GET['tanggal_lahir_max'] : '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pekerjaan</label>
                                        <select name="pekerjaan" class="form-select">
                                            <option value="">Semua Pekerjaan</option>
                                            <?php foreach ($pekerjaanList as $pekerjaan): ?>
                                                <option value="<?= $pekerjaan ?>"
                                                    <?= (isset($_GET['pekerjaan']) && $_GET['pekerjaan'] == $pekerjaan) ? 'selected' : '' ?>>
                                                    <?= $pekerjaan ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-12 mt-3 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-outline-primary me-2 rounded-pill">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                        <a href="index.php" class="btn btn-sm btn-outline-secondary rounded-pill">
                                            <i class="fas fa-sync me-1"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- Informasi Jumlah Data -->
                    <div class="alert alert-info mb-3" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        Jumlah Penduduk: <?= count($pendudukList) ?>
                    </div>


                    <!-- Tabel Data -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataPenduduk">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Umur</th>
                                    <th>Pekerjaan</th>
                                    <th>Jorong</th>
                                    <?php
                                        if ($_SESSION['user_role'] !== 'wali_nagari') {
                                    ?>
                                    <th class="text-center">Aksi</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendudukList as $penduduk): 
                                     $tanggalLahir = new DateTime($penduduk['tanggal_lahir']);
                                     $sekarang = new DateTime();
                                     $umur = $sekarang->diff($tanggalLahir)->y;
                                    ?>
                                    <tr>
                                        <td><?= $penduduk['id'] ?></td>
                                        <td><?= $penduduk['nama'] ?></td>
                                        <td><?= $penduduk['nik'] ?></td>
                                        <td><?= $penduduk['jenis_kelamin'] ?></td>
                                        <td><?= $umur ?></td>
                                        <td><?= $penduduk['tempat_lahir'] ?></td>
                                        <td><?= $penduduk['pekerjaan'] ?></td>
                                        <td><?= $penduduk['jorong'] ?></td>
                                        <?php
                                            if ($_SESSION['user_role'] !== 'wali_nagari') {
                                        ?>
                                        <td class="text-center">
                                            <div class="btn-group gap-2" role="group">
                                                <a href="edit.php?id=<?= $penduduk['id'] ?>" class="btn btn-outline-warning btn-sm rounded-circle">
                                                    <i class="fas fa-edit fa-xs"></i>
                                                </a>
                                                <a href="?action=delete&id=<?= $penduduk['id'] ?>" class="btn btn-outline-danger btn-sm rounded-circle" onclick="return confirm('Yakin ingin menghapus data?')">
                                                    <i class="fas fa-trash fa-xs"></i>
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
                <h5 class="modal-title" id="cetakModalLabel">Cetak Laporan Penduduk</h5>
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
                        <label class="form-label">Umur Min</label>
                        <select name="umur_min" class="form-select">
                            <option value="">Semua</option>
                            <?php foreach ($umurRentang as $rentang): ?>
                                <option value="<?= $rentang['min'] ?>">
                                    <?= $rentang['label'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Umur Maks</label>
                        <select name="umur_max" class="form-select">
                            <option value="">Semua</option>
                            <?php foreach ($umurRentang as $rentang): ?>
                                <option value="<?= $rentang['max'] ?>">
                                    <?= $rentang['label'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <select name="pekerjaan" class="form-select">
                            <option value="">Semua Pekerjaan</option>
                            <?php foreach ($pekerjaanList as $pekerjaan): ?>
                                <option value="<?= $pekerjaan ?>">
                                    <?= $pekerjaan ?>
                                </option>
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


<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JS (pastikan sesuai dengan versi yang digunakan) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
       

        // Event listener untuk tombol cetak
        $('#cetakLaporan').on('click', function() {
            console.log("Tombol Cetak diklik!"); // Debug log
            $('#cetakModal').modal('show');
        });

        $('#btnCetak').on('click', function() {
            console.log("Tombol Cetak di modal diklik!"); // Debug log
            const formData = $('#formCetak').serialize();
            const url = 'cetak_laporan.php?' + formData;
            console.log("URL untuk cetak:", url); // Debug log
            window.open(url, '_blank');
            $('#cetakModal').modal('hide');
        });
        $('#dataPenduduk').DataTable({
            "order": [
                [0, "asc"]
            ],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data yang ditampilkan",
                "infoFiltered": "(disaring dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
</script>




<?php
$content = ob_get_clean(); // Get buffered content
include '../../views/layout.php'; // Include layout with content