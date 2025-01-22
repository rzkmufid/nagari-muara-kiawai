<?php
require_once __DIR__ . '../../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $role = $_POST['role']; // Ambil role dari form

    $userModel = new User();
    if ($userModel->register($username, $password, $role)) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: User tidak berhasil didaftarkan.";
    }
}