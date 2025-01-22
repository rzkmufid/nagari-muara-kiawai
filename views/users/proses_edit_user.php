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
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Bisa kosong
    $role = $_POST['role'];


    // Jika password kosong, kirim null
    $password = empty($password) ? null : $password;


    if ($userModel->updateUser($user_id, $username, $password, $role)) {
        $_SESSION['success_message'] = "User berhasil diupdate.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Gagal mengupdate user.";
        header("Location: edit_user.php?id=" . $user_id);
        exit();
    }
}