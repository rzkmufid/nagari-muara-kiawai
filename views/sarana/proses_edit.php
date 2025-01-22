<?php
require_once __DIR__ . '/../../models/Sarana.php';


// Inisialisasi model
$saranaModel = new Sarana();


// Proses edit data sarana
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'jenis' => $_POST['jenis'],
        'jumlah' => $_POST['jumlah'],
        'kondisi' => $_POST['kondisi'],
        'keterangan' => $_POST['keterangan'] ?? ''
    ];
    
    $id = intval($_GET['id']);
    
    if ($saranaModel->update($id, $data)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: Data tidak berhasil diupdate.";
    }
}