<?php
require_once __DIR__ . '/../../models/Jorong.php';




// Inisialisasi model
$jorongModel = new Jorong();




// Proses edit data jorong
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nama_jorong' => $_POST['nama_jorong'],
        'kepala_jorong' => $_POST['kepala_jorong'],
        'luas_wilayah' => $_POST['luas_wilayah'],
        'jumlah_kk' => $_POST['jumlah_kk'],
        'keterangan' => $_POST['keterangan'] ?? ''
    ];
    
    $id = intval($_GET['id']);
    
    if ($jorongModel->update($id, $data)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: Data tidak berhasil diupdate.";
    }
}