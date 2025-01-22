    <?php
    require_once __DIR__ . '/../../models/Sarana.php';


    // Inisialisasi model
    $saranaModel = new Sarana();


    // Proses delete jika ada parameter
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $saranaModel->delete($id);
        header("Location: index.php");
        exit();
    }


    // Ambil semua data sarana
    $saranaList = $saranaModel->readAll();


    $pageTitle = "Data Sarana dan Prasarana";
    ob_start(); // Start output buffering
    ?>


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-building me-2"></i> Data Sarana dan Prasarana
                        </h3>
                        <div class="btn-group" role="group">
                            <a href="tambah.php" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Sarana
                            </a>
                            <button id="cetakLaporan" class="btn btn-light btn-sm">
                                <i class="fas fa-print me-1"></i> Cetak Laporan
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Informasi Statistik Sarana -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="card bg-light border-0">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-check-circle me-2 text-success"></i>Sarana Baik
                                            </h5>
                                        </div>
                                        <p class="card-text display-6 text-success mb-0">
                                            <?= count(array_filter($saranaList, function($s) { return $s['kondisi'] == 'Baik'; })) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light border-0">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Sarana Rusak Ringan
                                            </h5>
                                        </div>
                                        <p class="card-text display-6 text-warning mb-0">
                                            <?= count(array_filter($saranaList, function($s) { return $s['kondisi'] == 'Rusak Ringan'; })) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light border-0">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-times-circle me-2 text-danger"></i>Sarana Rusak Berat
                                            </h5>
                                        </div>
                                        <p class="card-text display-6 text-danger mb-0">
                                            <?= count(array_filter($saranaList, function($s) { return $s['kondisi'] == 'Rusak Berat'; })) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Tabel Sarana -->
                        <div class="table-responsive">
                            <table class="table table-hover" id="dataSarana">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Jenis</th>
                                        <th>Jumlah</th>
                                        <th>Kondisi</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($saranaList as $sarana): ?>
                                    <tr>
                                        <td><?= $sarana['id'] ?></td>
                                        <td><?= $sarana['jenis'] ?></td>
                                        <td><?= $sarana['jumlah'] ?></td>
                                        <td>
                                            <span class="badge 
                                                <?= $sarana['kondisi'] == 'Baik' ? 'bg-success' : 
                                                ($sarana['kondisi'] == 'Rusak Ringan' ? 'bg-warning' : 'bg-danger') ?>">
                                                <?= $sarana['kondisi'] ?>
                                            </span>
                                        </td>
                                        <td><?= $sarana['keterangan'] ?></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="edit.php?id=<?= $sarana['id'] ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=delete&id=<?= $sarana['id'] ?>" 
                                                class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Yakin ingin menghapus data?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
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
                    <h5 class="modal-title" id="cetakModalLabel">Cetak Laporan Sarana</h5>
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
                            <label class="form-label">Filter Kondisi</label>
                            <select class="form-select" name="kondisi">
                                <option value="">Semua Kondisi</option>
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
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
    // Tambahkan console.log untuk debugging
    $(document).ready(function() {
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
});
    </script>


    <?php
    $content = ob_get_clean(); // Get buffered content
    include __DIR__ . '/../layout.php'; // Include layout with content