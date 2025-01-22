<?php
require_once __DIR__ . '/../../models/Penduduk.php';


// Inisialisasi model
$pendudukModel = new Penduduk();


// Ambil pekerjaan dari parameter
$pekerjaan = $_GET['pekerjaan'] ?? '';


// Ambil detail penduduk berdasarkan pekerjaan
$pendudukPekerjaan = $pendudukModel->getPendudukByPekerjaan($pekerjaan);

$pageTitle = "Detail Penduduk - $pekerjaan";
ob_start(); // Start output buffering
?>


<div class="container-fluid mt-5">
    <h2>Detail Penduduk dengan Pekerjaan: <?= htmlspecialchars($pekerjaan) ?></h2>
    
    <table class="table table-striped" id="detailPekerjaanTable">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIK</th>
                <th>Jenis Kelamin</th>
                <th>Umur</th>
                <th>Jorong</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pendudukPekerjaan as $penduduk): ?>
            <tr>
                <td><?= $penduduk['nama'] ?></td>
                <td><?= $penduduk['nik'] ?></td>
                <td><?= $penduduk['jenis_kelamin'] ?></td>
                <td><?= $penduduk['umur'] ?></td>
                <td><?= $penduduk['jorong'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script>
$(document).ready(function() {
    $('#detailPekerjaanTable').DataTable({
        "order": [[0, "asc"]]
    });
});
</script>


<?php
$content = ob_get_clean(); // Get buffered content
include '../../views/layout.php'; // Include layout with content