<?php
session_start();
require_once __DIR__ . '../../../models/User.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $userModel = new User();
    $user = $userModel->login($username, $password);


    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: /nagari-muara-kiawai/views/dashboard.php");
        exit();
    } else {
        // Simpan pesan kesalahan ke dalam session
        $_SESSION['login_error'] = "Username atau password salah.";
        header("Location: login.php");
        exit();
    }
}