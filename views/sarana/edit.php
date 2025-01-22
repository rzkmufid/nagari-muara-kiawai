<?php
require_once __DIR__ . '/../../models/Sarana.php';


// Inisialisasi model
$saranaModel = new Sarana();


// Ambil data sarana berdasarkan ID
$sarana = $saranaModel->readOne($_GET['id']);


if (!$sarana) {
    die("Data tidak ditemukan.");
}


$pageTitle = "Edit Sarana dan Prasarana";
ob_start(); // Start output buffering
?>


<div class="container-fluid mt-5">
    <h2>Edit Sarana dan Prasarana</h2>
    <form method="POST" action="proses_edit.php?id=<?= $sarana['id'] ?>">
        <div class="mb-3">
            <label class="form-label">Jenis</label>
            <input type="text" class="form-control" name="jenis" value="<?= $sarana['jenis'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" class="form-control" name="jumlah" value="<?= $sarana['jumlah'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kondisi</label>
            <select class="form-select" name="kondisi" required>
                <option value="Baik" <?= $sarana['kondisi'] == 'Baik' ? 'selected' : '' ?>>Baik</option>
                <option value="Kurang Baik" <?= $sarana['kondisi'] == 'Kurang Baik' ? 'selected' : '' ?>>Kurang Baik</option>
                <option value="Rusak" <?= $sarana['kondisi'] == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" name="keterangan"><?= $sarana['keterangan'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>


<?php
$content = ob_get_clean(); // Get buffered content
include __DIR__ . '/../layout.php'; // Include layout with content