<?php
session_start();


// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


require_once __DIR__ . '../../../models/User.php';


$userModel = new User();


// Cek apakah user adalah admin
if (!$userModel->isAdmin($_SESSION['user_id'])) {
    echo "Akses ditolak. Hanya admin yang dapat mengakses halaman ini.";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];


    if ($userModel->register($username, $password, $role)) {
        $_SESSION['success_message'] = "User berhasil ditambahkan.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Gagal menambahkan user.";
        header("Location: tambah_user.php");
        exit();
    }
}