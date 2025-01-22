<?php
require_once __DIR__ . '/../../models/Jorong.php';




// Inisialisasi model
$jorongModel = new Jorong();




// Ambil data jorong berdasarkan ID
$jorong = $jorongModel->readOne($_GET['id']);




if (!$jorong) {
    die("Data tidak ditemukan.");
}




$pageTitle = "Edit Jorong";
ob_start(); // Start output buffering
?>


<div class="container-fluid mt-5">
    <h2>Edit Jorong</h2>
    <form method="POST" action="proses_edit.php?id=<?= $jorong['id'] ?>">
        <div class="mb-3">
            <label class="form-label">Nama Jorong</label>
            <input type="text" class="form-control" name="nama_jorong" value="<?= $jorong['nama_jorong'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kepala Jorong</label>
            <input type="text" class="form-control" name="kepala_jorong" value="<?= $jorong['kepala_jorong'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Luas Wilayah (Ha)</label>
            <input type="number" class="form-control" name="luas_wilayah" value="<?= $jorong['luas_wilayah'] ?>" step="0.01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah Kartu Keluarga</label>
            <input type="number" class="form-control" name="jumlah_kk" value="<?= $jorong['jumlah_kk'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" name="keterangan"><?= $jorong['keterangan'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>


<?php
$content = ob_get_clean(); // Get buffered content
include '../../views/layout.php'; // Include layout with content