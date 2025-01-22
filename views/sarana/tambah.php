<?php
$pageTitle = "Tambah Sarana dan Prasarana";
ob_start(); // Start output buffering
?>


<div class="container-fluid mt-5">
    <h2>Tambah Sarana dan Prasarana</h2>
    <form method="POST" action="proses_tambah.php">
        <div class="mb-3">
            <label class="form-label">Jenis</label>
            <input type="text" class="form-control" name="jenis" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" class="form-control" name="jumlah" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kondisi</label>
            <select class="form-select" name="kondisi" required>
                <option value="Baik">Baik</option>
                <option value="Kurang Baik">Kurang Baik</option>
                <option value="Rusak">Rusak</option>
            </select>
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
include __DIR__ . '/../layout.php'; // Include layout with content