<?php



// Definisikan judul halaman
$pageTitle = "Selamat Datang di Sistem Informasi Nagari Muara Kiawai";


// Mulai buffer output
ob_start();


// Definisikan basis path
define('BASE_PATH', __DIR__);


// Router sederhana
$request = $_SERVER['REQUEST_URI'];
$base_path = '/nagari-muara-kiawai/';


// Hilangkan base path dari request
$path = str_replace($base_path, '', $request);


// Pisahkan path
$parts = explode('/', $path);


// Default routing
$file = 'views/dashboard.php';


// Routing custom
switch($parts[0]) {
    case 'auth':
        $file = 'views/auth/' . ($parts[1] ?? 'login.php');
        break;
    case 'penduduk':
        $file = 'views/penduduk/' . ($parts[1] ?? 'index.php');
        break;
    case 'sarana':
        $file = 'views/sarana/' . ($parts[1] ?? 'index.php');
        break;
    case '':
        $file = 'views/dashboard.php';
        break;
}


// Cek apakah file ada
if (!isset($_SESSION['user_id']) && $parts[0] !== 'auth') {
    header("Location: {$base_path}/views/auth/login.php");
    exit();
}


// Jika file tidak ada, tampilkan error
if (!file_exists(BASE_PATH . '/' . $file)) {
    http_response_code(404);
    die("Halaman tidak ditemukan");
}


// Include file yang sesuai
require_once BASE_PATH . '/' . $file;
?>


<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="text-center">Sistem Informasi Nagari Muara Kiawai</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">Selamat Datang</div>
                                <div class="card-body">
                                    <p>Selamat datang di Sistem Informasi Nagari Muara Kiawai. 
                                    Sistem ini dirancang untuk membantu pengelolaan data dan informasi 
                                    kependudukan serta sarana prasarana di Nagari Muara Kiawai.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">Akses Cepat</div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="/views/dashboard.php" class="list-group-item list-group-item-action">
                                            Dashboard Statistik
                                        </a>
                                        <a href="views/penduduk/index.php" class="list-group-item list-group-item-action">
                                            Data Penduduk
                                        </a>
                                        <a href="views/sarana/index.php" class="list-group-item list-group-item-action">
                                            Sarana dan Prasarana
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Informasi Nagari</div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th>Nama Nagari</th>
                                            <td>Muara Kiawai</td>
                                        </tr>
                                        <tr>
                                            <th>Kecamatan</th>
                                            <td>Gunung Tuleh</td>
                                        </tr>
                                        <tr>
                                            <th>Kabupaten</th>
                                            <td>Pasaman Barat</td>
                                        </tr>
                                        <tr>
                                            <th>Luas Wilayah</th>
                                            <td>163.99 Ha</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
// Ambil konten dari buffer
$content = ob_get_clean();


// Sertakan layout
include 'views/layout.php';
?>