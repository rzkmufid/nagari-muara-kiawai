<?php
require_once __DIR__ . '/../../models/Penduduk.php';


// Inisialisasi model
$pendudukModel = new Penduduk();


// Ambil statistik umur
$statistikUmur = $pendudukModel->getStatistikUmurJenisKelamin();


$pageTitle = "Keadaan Penduduk Berdasarkan Umur Tahun 2024";
ob_start(); // Start output buffering
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i> Statistik Penduduk Berdasarkan Umur
                    </h3>
                    <div class="text-muted text-white">
                        Tahun 2024
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Statistik Tabel -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="statistikUmurTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Golongan Umur</th>
                                    <th>Laki-laki</th>
                                    <th>Perempuan</th>
                                    <th>Jumlah</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalLakiLaki = 0;
                                $totalPerempuan = 0;
                                $totalJumlah = 0;
                                
                                foreach ($statistikUmur as $index => $data): 
                                    $jumlah = $data['laki_laki'] + $data['perempuan'];
                                    $totalLakiLaki += $data['laki_laki'];
                                    $totalPerempuan += $data['perempuan'];
                                    $totalJumlah += $jumlah;
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= $data['golongan_umur'] ?></td>
                                    <td><?= $data['laki_laki'] ?></td>
                                    <td><?= $data['perempuan'] ?></td>
                                    <td><?= $jumlah ?></td>
                                    <td class="text-center">
                                        <a href="detail_umur.php?golongan_umur=<?= urlencode($data['golongan_umur']); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-center">Total</th>
                                    <th><?= $totalLakiLaki ?></th>
                                    <th><?= $totalPerempuan ?></th>
                                    <th><?= $totalJumlah ?></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <!-- Informasi Statistik -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-male me-2 text-primary"></i>Total Laki-laki
                                    </h5>
                                    <p class="card-text display-6 text-primary"><?= $totalLakiLaki ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-female me-2 text-danger"></i>Total Perempuan
                                    </h5>
                                    <p class="card-text display-6 text-danger"><?= $totalPerempuan ?></p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Grafik Statistik Umur -->
                    <div class="card mt-4 border-0 shadow-sm">
                        <div class="card-body">
                            <canvas id="statistikUmurChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk chart
    const golonganUmur = <?= json_encode(array_column($statistikUmur, 'golongan_umur')) ?>;
    const lakiLaki = <?= json_encode(array_column($statistikUmur, 'laki_laki')) ?>;
    const perempuan = <?= json_encode(array_column($statistikUmur, 'perempuan')) ?>;


    // Buat chart
    const ctx = document.getElementById('statistikUmurChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: golonganUmur,
            datasets: [
                {
                    label: 'Laki-laki',
                    data: lakiLaki,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)'
                },
                {
                    label: 'Perempuan',
                    data: perempuan,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Komposisi Penduduk Berdasarkan Umur dan Jenis Kelamin'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            }
        }
    });


    // DataTables
    $('#statistikUmurTable').DataTable({
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