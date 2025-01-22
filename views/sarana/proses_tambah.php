<?php
require_once __DIR__ . '/../../models/Sarana.php';


// Inisialisasi model
$saranaModel = new Sarana();


// Proses tambah data sarana
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'jenis' => $_POST['jenis'],
        'jumlah' => $_POST['jumlah'],
        'kondisi' => $_POST['kondisi'],
        'keterangan' => $_POST['keterangan'] ?? ''
    ];
    
    if ($saranaModel->create($data)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: Data tidak berhasil disimpan.";
    }
}