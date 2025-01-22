<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}// Cek autentikasi
if (!isset($_SESSION['user_id'])) {
    header("Location: /nagari-muara-kiawai/views/auth/login.php");
    exit();
}

if (in_array($pageTitle, ["Manajemen User", "Edit User"])) {
    // Cek apakah user adalah admin
    if (!$userModel->isAdmin($_SESSION['user_id'])) {
        echo "Akses ditolak. Hanya admin yang dapat mengakses halaman ini.";
        exit();
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Nagari Muara Kiawai' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

        /* Perbaikan Sidebar Styles */
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 300px;
            /* Tetapkan lebar sidebar */
            z-index: 100;
            padding-top: 48px;
            background-color: #2c3e50;
            overflow-y: auto;
            /* Tambahkan scrollbar jika konten terlalu panjang */
        }


        .sidebar .nav-link {
            font-weight: 500;
            color: #e9ecef;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }


        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }


        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: #34495e;
        }


        /* Submenu Styles dengan Animasi Lebih Halus */
        .sidebar .submenu {
            max-height: 0;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
            transform-origin: top;
            transform: scaleY(0);
        }


        .sidebar .nav-item.menu-open .submenu {
            max-height: 300px;
            /* Sesuaikan dengan jumlah submenu */
            opacity: 1;
            visibility: visible;
            transform: scaleY(1);
        }

        .sidebar .navbar-brand {
            color: #ffffff;
        }


        .submenu .nav-link {
            padding-left: 3rem !important;
            background-color: #2c3e50;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }


        .submenu .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-left-color: #3498db;
        }


        .submenu .nav-link.active {
            background-color: #34495e;
            border-left-color: #3498db;
        }


        /* Caret Rotation */
        .sidebar .nav-link-caret {
            transition: transform 0.3s ease;
        }


        .sidebar .nav-item.menu-open .nav-link-caret {
            transform: rotate(90deg);
            color: #3498db;
        }


        /* Main Content Adjustment */
        .main-content {
            margin-left: 300px;
            margin-top: 50px;
            /* Sesuaikan dengan lebar sidebar */
            width: calc(100% - 300px);
            padding: 20px;
        }

        i {
            width: 1rem;
        }

        .navbars {
            background-color: #ffffff;
            border-bottom: 1px solid #e1e1e1;
            margin-left: 300px;
            width: calc(100% - 300px);
        }


        /* Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
                padding-top: 0;
            }


            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar">
                <div class="position-sticky">
                    <a href="#" class="navbar-brand text-center d-block py-3">
                        <i class="fas fa-chart-pie"></i>  Nagari Muara Kiawai
                    </a>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'dashboard.php') ? 'active' : '' ?>" href="/nagari-muara-kiawai/views/dashboard.php">
                                <span><i class="fas fa-home me-2"></i> Dashboard </span>
                                <i class=""></i>
                            </a>
                        </li>

                        <li class="nav-item <?= strpos($_SERVER['PHP_SELF'], 'penduduk') !== false ? 'menu-open' : '' ?>">
                            <a class="nav-link" href="#" data-toggle="submenu">
                                <span>
                                    <i class="fas fa-users me-2"></i> Data Penduduk
                                </span>
                                <i class="fas fa-chevron-right nav-link-caret"></i>
                            </a>
                            <ul class="nav flex-column submenu">
                                <li class="nav-item">
                                    <a class="nav-link <?= $_SERVER['PHP_SELF'] == '/nagari-muara-kiawai/views/penduduk/index.php' ? 'active' : '' ?>"
                                        href="/nagari-muara-kiawai/views/penduduk/">
                                        <span><i class="fas fa-list me-2"></i> Daftar Penduduk</span>
                                        <i></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'statistik_umur.php') ? 'active' : '' ?>"
                                        href="/nagari-muara-kiawai/views/penduduk/statistik_umur.php">
                                        <span><i class="fas fa-chart-bar me-2"></i> Statistik Umur</span>
                                        <i></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'statistik_pekerjaan.php') ? 'active' : '' ?>"
                                        href="/nagari-muara-kiawai/views/penduduk/statistik_pekerjaan.php">
                                        <span><i class="fas fa-briefcase me-2"></i> Statistik Pekerjaan</span>
                                        <i></i>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'sarana') ? 'active' : '' ?>" href="/nagari-muara-kiawai/views/sarana/">
                                <span> <i class="fas fa-building me-2"></i> Data Sarana
                                </span>
                                <i class=""></i>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'jorong') ? 'active' : '' ?>" href="/nagari-muara-kiawai/views/jorong/">
                                <span> <i class="fas fa-map-marker-alt me-2"></i> Data Jorong
                                </span>
                                <i></i>
                            </a>
                        </li>
                        <?php if ($_SESSION['user_role'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/nagari-muara-kiawai/views/users/">
                                    <span> <i class="fas fa-user-plus me-2"></i> Tambah User
                                    </span>
                                    <i></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/nagari-muara-kiawai/views/auth/logout.php">
                                <span><i class="fas fa-sign-out-alt me-2"></i> Logout</span>
                                <i></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>




            <!-- Konten Utama -->
            <main class="col-md-10 ms-sm-auto main-content">
                <div class="navbars top-bar d-flex justify-content-between align-items-center fixed-top bg-white shadow-sm p-3">
                    <h2 class="h4 mb-0"><?= $pageTitle ?? 'Dashboard' ?></h2>
                    <div class="user-info">
                        <span class="me-2">
                            <i class="fas fa-user"></i> <?= $_SESSION['username'] ?? 'Pengguna' ?>
                        </span>
                    </div>
                </div>

                <?= $content ?>
            </main>
        </div>
    </div>




    <!-- Bootstrap JS (PENTING: Letakkan di AKHIR body) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

                            <!-- Di bagian bawah layout.php -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle submenu
            const submenuToggle = document.querySelectorAll('.nav-link[data-toggle="submenu"]');

            submenuToggle.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parentItem = this.closest('.nav-item');

                    // Tutup submenu lain yang terbuka
                    document.querySelectorAll('.nav-item.menu-open').forEach(function(openItem) {
                        if (openItem !== parentItem) {
                            openItem.classList.remove('menu-open');
                        }
                    });


                    // Toggle submenu yang diklik
                    parentItem.classList.toggle('menu-open');
                });
            });
        });
    </script>
</body>

</html>