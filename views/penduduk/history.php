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

    // Ambil semua data sarana
    $historyList = $pendudukModel->readHistory();


    $pageTitle = "History";
    ob_start(); // Start output buffering
    $n = 1;
    ?>


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-history me-2"></i> History
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        


                        <!-- Tabel Sarana -->
                        <div class="table-responsive">
                        <table class="table table-hover" id="dataPenduduk">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Umur</th>
                                    <th>Pekerjaan</th>
                                    <th>Jorong</th>
                                    <th>Tanggal Berubah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historyList as $history): ?>
                                    <tr>
                                        <td><?= $n++ ?></td>
                                        <td><?= $history['nama'] ?></td>
                                        <td><?= $history['nik'] ?></td>
                                        <td><?= $history['jenis_kelamin'] ?></td>
                                        <?php
                                            $today = new DateTime();
                                            $birthdate = new DateTime($history['tanggal_lahir']);
                                            $interval = $today->diff($birthdate);
                                            $umur = $interval->format('%y');
                                        ?>
                                        <td><?= $umur ?></td>
                                        <td><?= $history['pekerjaan'] ?></td>
                                        <td><?= $history['jorong'] ?></td>
                                        <td><?= $history['created_at'] ?></td>
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


    <!-- Di dalam <head> atau di akhir <body> layout.php -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    $content = ob_get_clean(); // Get buffered content
    include __DIR__ . '/../layout.php'; // Include layout with content