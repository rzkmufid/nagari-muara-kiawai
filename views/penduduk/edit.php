<?php
require_once __DIR__ . '/../../models/Penduduk.php';
require_once __DIR__ . '/../../models/Jorong.php';




// Inisialisasi model
$pendudukModel = new Penduduk();
$jorongModel = new Jorong();




// Ambil data penduduk berdasarkan ID
$penduduk = $pendudukModel->readOne($_GET['id']);




if (!$penduduk) {
    die("Data tidak ditemukan.");
}




// Ambil daftar jorong
$jorongList = $jorongModel->readAll();




$pageTitle = "Edit Penduduk";
ob_start(); // Start output buffering
?>




<div class="container-fluid mt-5">
    <h2>Edit Penduduk</h2>
    <form method="POST" action="proses_edit.php?id=<?= $penduduk['id'] ?>">
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($penduduk['nama']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">NIK</label>
            <input type="text" class="form-control" name="nik" value="<?= htmlspecialchars($penduduk['nik']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select class="form-select" name="jenis_kelamin" required>
                <option value="Laki-laki" <?= $penduduk['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="Perempuan" <?= $penduduk['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Umur</label>
            <input type="number" class="form-control" name="umur" value="<?= $penduduk['umur'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Pekerjaan</label>
            <input type="text" class="form-control" name="pekerjaan" value="<?= htmlspecialchars($penduduk['pekerjaan']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jorong</label>
            <select class="form-select" name="jorong" required>
                <option value="">Pilih Jorong</option>
                <?php foreach ($jorongList as $jorong): ?>
                    <option value="<?= htmlspecialchars($jorong['nama_jorong']) ?>" 
                        <?= $penduduk['jorong'] == $jorong['nama_jorong'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($jorong['nama_jorong']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>




<?php
$content = ob_get_clean(); // Get buffered content
include '../layout.php'; // Include layout with content