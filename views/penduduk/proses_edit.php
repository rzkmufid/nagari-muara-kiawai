<?php
require_once __DIR__ . '/../../models/Penduduk.php';


// Inisialisasi model
$pendudukModel = new Penduduk();


// Proses edit data penduduk
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nama' => $_POST['nama'],
        'nik' => $_POST['nik'],
        'jenis_kelamin' => $_POST['jenis_kelamin'],
        'umur' => $_POST['umur'],
        'pekerjaan' => $_POST['pekerjaan'],
        'jorong' => $_POST['jorong'],
    ];
    
    $id = intval($_GET['id']);
    
    if ($pendudukModel->update($id, $data)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: Data tidak berhasil diupdate.";
    }
}