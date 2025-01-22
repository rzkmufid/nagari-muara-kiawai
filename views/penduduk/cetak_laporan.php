<?php
require_once __DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php';
require_once __DIR__ . '/../../models/Penduduk.php';


// Inisialisasi model
$pendudukModel = new Penduduk();


// Ambil parameter dari GET
$jenisLaporan = $_GET['jenis_laporan'] ?? 'pdf';
$filters = [];


// Tambahkan filter sesuai parameter
if (isset($_GET['umur_min']) && $_GET['umur_min'] !== '') {
    $filters['umur_min'] = $_GET['umur_min'];
}


if (isset($_GET['umur_max']) && $_GET['umur_max'] !== '') {
    $filters['umur_max'] = $_GET['umur_max'];
}


if (isset($_GET['pekerjaan']) && $_GET['pekerjaan'] !== '') {
    $filters['pekerjaan'] = $_GET['pekerjaan'];
}


// Ambil data penduduk dengan filter
$pendudukList = empty($filters) ? $pendudukModel->readAll() : $pendudukModel->readAllWithFilter($filters);


// Hitung statistik
$statistik = [
    'total_penduduk' => count($pendudukList),
    'total_laki' => count(array_filter($pendudukList, function($p) { return $p['jenis_kelamin'] == 'Laki-laki'; })),
    'total_perempuan' => count(array_filter($pendudukList, function($p) { return $p['jenis_kelamin'] == 'Perempuan'; }))
];


// Jika Excel dipilih
if ($jenisLaporan == 'excel') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Laporan_Penduduk_' . date('Y-m-d') . '.xls"');

    
    echo "<table border='1'>";
    echo "<tr><th colspan='5'>LAPORAN PENDUDUK</th></tr>";
    echo "<tr><th colspan='5'>Tahun " . date('Y') . "</th></tr>";
    echo "<tr><th>ID</th><th>Nama</th><th>NIK</th><th>Jenis Kelamin</th><th>Pekerjaan</th></tr>";
    
    foreach ($pendudukList as $penduduk) {
        echo "<tr>";
        echo "<td>" . $penduduk['id'] . "</td>";
        echo "<td>" . $penduduk['nama'] . "</td>";
        echo "<td>" . $penduduk['nik'] . "</td>";
        echo "<td>" . $penduduk['jenis_kelamin'] . "</td>";
        echo "<td>" . $penduduk['pekerjaan'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    exit;
}




// Jika PDF dipilih (default)
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// Informasi dokumen
$pdf->SetCreator('Nagari Muara Kiawai');
$pdf->SetAuthor('Pemerintah Nagari');
$pdf->SetTitle('Laporan Penduduk');
$pdf->SetSubject('Laporan Tahunan');


// Hapus header dan footer default
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


// Atur margin
$pdf->SetMargins(15, 15, 15);


// Tambah halaman
$pdf->AddPage('P', 'A4'); // Portrait


// Judul Laporan
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'PEMERINTAH NAGARI MUARA KIAWAI', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 7, 'LAPORAN PENDUDUK', 0, 1, 'C');
$pdf->Cell(0, 7, 'TAHUN ' . date('Y'), 0, 1, 'C');


// Garis pemisah
$pdf->Line(15, 40, 195, 40);


// Statistik
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(10);
$pdf->Cell(70, 7, 'Total Penduduk', 1);
$pdf->Cell(0, 7, $statistik['total_penduduk'], 1);
$pdf->Ln();
$pdf->Cell(70, 7, 'Total Laki-laki', 1);
$pdf->Cell(0, 7, $statistik['total_laki'], 1);
$pdf->Ln();
$pdf->Cell(70, 7, 'Total Perempuan', 1);
$pdf->Cell(0, 7, $statistik['total_perempuan'], 1);


// Tabel Data Penduduk
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);


// Header Tabel
$pdf->Cell(15, 7, 'ID', 1, 0, 'C');
$pdf->Cell(40, 7, 'Nama', 1, 0, 'C');
$pdf->Cell(30, 7, 'NIK', 1, 0, 'C');
$pdf->Cell(30, 7, 'Jenis Kelamin', 1, 0, 'C');
$pdf->Cell(40, 7, 'Pekerjaan', 1, 1, 'C');


// Data Tabel
$pdf->SetFont('helvetica', '', 10);
foreach ($pendudukList as $penduduk) {
    $pdf->Cell(15, 7, $penduduk['id'], 1);
    $pdf->Cell(40, 7, $penduduk['nama'], 1);
    $pdf->Cell(30, 7, $penduduk['nik'], 1);
    $pdf->Cell(30, 7, $penduduk['jenis_kelamin'], 1);
    $pdf->Cell(40, 7, $penduduk['pekerjaan'], 1, 1);
}


// Tanda tangan (opsional)
$pdf->Ln(20);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Muara Kiawai, ' . date('d F Y'), 0, 1, 'R');
$pdf->Cell(0, 7, 'Kepala Nagari,', 0, 1, 'R');
$pdf->Ln(20);
$pdf->Cell(0, 7, '( ........................ )', 0, 1, 'R');


// Keluarkan PDF
$pdf->Output('Laporan_Penduduk_' . date('Y-m-d') . '.pdf', 'I');