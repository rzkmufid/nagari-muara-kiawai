<?php
require_once __DIR__ . '../../models/Penduduk.php';
require_once __DIR__ . '../../models/Sarana.php'; // Asumsikan Anda sudah membuat model Sarana


// Inisialisasi model
$pendudukModel = new Penduduk();
$saranaModel = new Sarana(); // Asumsikan Anda sudah membuat model Sarana


// Ambil data untuk dashboard
$totalPenduduk = count($pendudukModel->readAll());
$pendudukByJenisKelamin = $pendudukModel->getPendudukByJenisKelamin();
$pendudukByPekerjaan = $pendudukModel->getPendudukByPekerjaanAll();
$totalSarana = count($saranaModel->readAll());


$pageTitle = "Dashboard Nagari Muara Kiawai";
ob_start(); // Start output buffering
?>


<div class="container-fluid mt-4">
    <h1 class="mb-4">Dashboard Nagari Muara Kiawai</h1>


    <div class="row">
        <!-- Total Penduduk -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-header">Total Penduduk</div>
                <div class="card-body">
                    <h2 class="card-title"><?= $totalPenduduk ?> Jiwa</h2>
                </div>
            </div>
        </div>


        <!-- Total Sarana -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success">
                <div class="card-header">Total Sarana</div>
                <div class="card-body">
                    <h2 class="card-title"><?= $totalSarana ?> Fasilitas</h2>
                </div>
            </div>
        </div>


        <!-- Statistik Lainnya -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-info">
                <div class="card-header">Statistik Nagari</div>
                <div class="card-body">
                    <p class="card-text">
                        Luas Wilayah: 163.99 Ha<br>
                        Jorong: 1<br>
                        Ketinggian: 261.824 mdpl
                    </p>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <!-- Grafik Penduduk Berdasarkan Jenis Kelamin -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">Komposisi Penduduk Berdasarkan Jenis Kelamin</div>
                <div class="card-body">
                    <canvas id="jenisKelaminChart"></canvas>
                </div>
            </div>
        </div>


        <!-- Grafik Penduduk Berdasarkan Pekerjaan -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">Komposisi Penduduk Berdasarkan Pekerjaan</div>
                <div class="card-body">
                    <canvas id="pekerjaanChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Tambahkan script untuk Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Jenis Kelamin
    var jenisKelaminCtx = document.getElementById('jenisKelaminChart').getContext('2d');
    var jenisKelaminData = {
        labels: <?= json_encode(array_keys($pendudukByJenisKelamin)) ?>,
        datasets: [{
            data: <?= json_encode(array_values($pendudukByJenisKelamin)) ?>,
            backgroundColor: ['#36A2EB', '#FF6384']
        }]
    };
    new Chart(jenisKelaminCtx, {
        type: 'pie',
        data: jenisKelaminData,
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Komposisi Penduduk Berdasarkan Jenis Kelamin'
            }
        }
    });


    // Grafik Pekerjaan
    var pekerjaanCtx = document.getElementById('pekerjaanChart').getContext('2d');
    var pekerjaanData = {
        labels: <?= json_encode(array_keys($pendudukByPekerjaan)) ?>,
        datasets: [{
            data: <?= json_encode(array_values($pendudukByPekerjaan)) ?>,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', 
                '#FF9F40', '#FF6384', '#C9CBCF', '#36A2EB', '#FFCE56'
            ]
        }]
    };
    new Chart(pekerjaanCtx, {
        type: 'bar',
        data: pekerjaanData,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Komposisi Penduduk Berdasarkan Pekerjaan'
                }
            }
        }
    });
</script>


<?php
$content = ob_get_clean(); // Get buffered content
include 'layout.php'; // Include layout with content