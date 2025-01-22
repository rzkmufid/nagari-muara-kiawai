<?php
$pageTitle = "Tambah Jorong";
ob_start(); // Start output buffering
?>


<div class="container-fluid mt-5">
    <h2>Tambah Jorong</h2>
    <form method="POST" action="proses_tambah.php">
        <div class="mb-3">
            <label class="form-label">Nama Jorong</label>
            <input type="text" class="form-control" name="nama_jorong" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kepala Jorong</label>
            <input type="text" class="form-control" name="kepala_jorong" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Luas Wilayah (Ha)</label>
            <input type="number" class="form-control" name="luas_wilayah" step="0.01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah Kartu Keluarga</label>
            <input type="number" class="form-control" name="jumlah_kk" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" name="keterangan"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>


<?php
$content = ob_get_clean(); // Get buffered content
include '../../views/layout.php'; // Include layout with content