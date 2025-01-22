<?php
require_once __DIR__ . '/../../models/Penduduk.php';


// Inisialisasi model
$pendudukModel = new Penduduk();


// Ambil statistik pekerjaan
$statistikPekerjaan = $pendudukModel->getPendudukStatistikPekerjaan();


$pageTitle = "Statistik Penduduk Berdasarkan Pekerjaan";
ob_start(); // Start output buffering
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-briefcase me-2"></i> Statistik Penduduk Berdasarkan Pekerjaan
                    </h3>
                    <div class="text-muted text-white">
                        Tahun 2024
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Grafik Pie -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <canvas id="statistikPekerjaanChart"></canvas>
                                </div>
                            </div>
                        </div>


                        <!-- Tabel Statistik -->
                        <div class="col-md-6">
                            <!-- Total Penduduk -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card bg-light border-0">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-users me-2 text-primary"></i>Total Penduduk
                                                </h5>
                                            </div>
                                            <p class="card-text display-6 text-primary mb-0">
                                                <?php 
                                                $totalPenduduk = array_sum(array_column($statistikPekerjaan, 'jumlah'));
                                                echo $totalPenduduk; 
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Tabel Statistik Pekerjaan -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="statistikPekerjaanTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Pekerjaan</th>
                                            <th>Jumlah</th>
                                            <th>Persentase</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($statistikPekerjaan as $pekerjaan): ?>
                                        <tr>
                                            <td><?= $pekerjaan['pekerjaan'] ?></td>
                                            <td><?= $pekerjaan['jumlah'] ?></td>
                                            <td><?= number_format($pekerjaan['persentase'], 2) ?>%</td>
                                            <td class="text-center">
                                                <a href="detail_pekerjaan.php?pekerjaan=<?= urlencode($pekerjaan['pekerjaan']) ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-info-circle me-1"></i> Detail
                                                </a>
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
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk chart
    const labels = <?= json_encode(array_column($statistikPekerjaan, 'pekerjaan')) ?>;
    const data = <?= json_encode(array_column($statistikPekerjaan, 'jumlah')) ?>;


    // Palet warna yang lebih konsisten
    const backgroundColors = [
        'rgba(54, 162, 235, 0.6)',   // Biru
        'rgba(255, 99, 132, 0.6)',   // Merah Muda
        'rgba(255, 206, 86, 0.6)',   // Kuning
        'rgba(75, 192, 192, 0.6)',   // Tosca
        'rgba(153, 102, 255, 0.6)',  // Ungu
        'rgba(255, 159, 64, 0.6)',   // Oranye
        'rgba(199, 199, 199, 0.6)',  // Abu-abu
        'rgba(83, 102, 255, 0.6)',   // Biru Tua
        'rgba(40, 159, 64, 0.6)'     // Hijau
    ];


    // Buat chart
    const ctx = document.getElementById('statistikPekerjaanChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: backgroundColors.slice(0, labels.length)
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Komposisi Penduduk Berdasarkan Pekerjaan'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });


    // DataTables
    $('#statistikPekerjaanTable').DataTable({
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