<?php
session_start(); // Pastikan session dimulai
require_once __DIR__ . '/../../models/Penduduk.php';


// Inisialisasi model
$pendudukModel = new Penduduk();


// Proses tambah data penduduk
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nama' => $_POST['nama'],
        'nik' => $_POST['nik'],
        'jenis_kelamin' => $_POST['jenis_kelamin'],
        'umur' => $_POST['umur'],
        'pekerjaan' => $_POST['pekerjaan'],
        'jorong' => $_POST['jorong'],
    ];
    
    // Validasi input
    $errors = [];


    // Validasi NIK
    if (empty($data['nik'])) {
        $errors[] = "NIK harus diisi.";
    } elseif (strlen($data['nik']) > 16) {
        $errors[] = "NIK tidak boleh lebih dari 16 karakter.";
    } elseif ($pendudukModel->nikSudahAda($data['nik'])) {
        $errors[] = "NIK sudah terdaftar.";
    }


    // Validasi nama
    if (empty($data['nama'])) {
        $errors[] = "Nama harus diisi.";
    }


    // Validasi umur
    if (empty($data['umur']) || $data['umur'] < 0) {
        $errors[] = "Umur harus diisi dan tidak boleh negatif.";
    }


    // Jika ada error, simpan ke session dan redirect
    if (!empty($errors)) {
        $_SESSION['error_messages'] = $errors;
        header("Location: tambah.php");
        exit();
    }
    
    // Jika lolos validasi, coba simpan
    if ($pendudukModel->create($data)) {
        $_SESSION['success_message'] = "Penduduk berhasil ditambahkan.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_messages'] = ["Gagal menyimpan data penduduk."];
        header("Location: tambah.php");
        exit();
    }
}