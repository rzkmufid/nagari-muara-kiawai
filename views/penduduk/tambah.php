<?php
session_start(); // Pastikan session dimulai
require_once __DIR__ . '/../../models/Jorong.php';


$pageTitle = "Tambah Penduduk";


// Inisialisasi model Jorong
$jorongModel = new Jorong();


// Ambil daftar jorong
$jorongList = $jorongModel->readAll();


ob_start(); // Start output buffering
?>


<div class="container-fluid mt-5">
    <h2>Tambah Penduduk</h2>

    <!-- Tampilkan pesan error -->
    <?php if (isset($_SESSION['error_messages'])): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($_SESSION['error_messages'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['error_messages']); ?>
        </div>
    <?php endif; ?>


    <form method="POST" action="proses_tambah.php">
        <!-- Form fields tetap sama -->
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="nama" required>
        </div>
        <div class="mb-3">
            <label class="form-label">NIK</label>
            <input type="text" class="form-control" name="nik" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select class="form-select" name="jenis_kelamin" required>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Umur</label>
            <input type="number" class="form-control" name="umur" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Pekerjaan</label>
            <input type="text" class="form-control" name="pekerjaan" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jorong</label>
            <select class="form-select" name="jorong" required>
                <option value="">Pilih Jorong</option>
                <?php foreach ($jorongList as $jorong): ?>
                    <option value="<?= htmlspecialchars($jorong['nama_jorong']) ?>">
                        <?= htmlspecialchars($jorong['nama_jorong']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
    <!-- Sisa form tetap sama -->
    </form>
</div>


<?php
$content = ob_get_clean(); // Get buffered content
include '../layout.php'; // Include layout with content
