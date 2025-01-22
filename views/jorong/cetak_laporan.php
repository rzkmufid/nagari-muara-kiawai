<?php
// Sertakan TCPDF
require_once __DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php';
require_once __DIR__ . '/../../models/Jorong.php';




// Inisialisasi model
$jorongModel = new Jorong();




// Ambil parameter dari GET
$jorongId = $_GET['jorong'] ?? null;




// Ambil data jorong
if ($jorongId) {
    $jorongList = $jorongModel->getJorongById($jorongId);
} else {
    $jorongList = $jorongModel->readAll();
}




// Statistik
$statistik = $jorongModel->getStatistik();




// Buat dokumen PDF baru
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);




// Informasi dokumen
$pdf->SetCreator('Nagari Muara Kiawai');
$pdf->SetAuthor('Pemerintah Nagari');
$pdf->SetTitle('Laporan Data Jorong');
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
$pdf->Cell(0, 7, 'LAPORAN DATA JORONG', 0, 1, 'C');
$pdf->Cell(0, 7, 'TAHUN ' . date('Y'), 0, 1, 'C');




// Garis pemisah
$pdf->Line(15, 40, 195, 40);




// Statistik
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(10);
$pdf->Cell(70, 7, 'Total Jorong', 1);
$pdf->Cell(0, 7, $statistik['total_jorong'], 1);
$pdf->Ln();
$pdf->Cell(70, 7, 'Total Kartu Keluarga', 1);
$pdf->Cell(0, 7, $statistik['total_kk'], 1);
$pdf->Ln();
$pdf->Cell(70, 7, 'Total Luas', 1);
$pdf->Cell(0, 7, $statistik['total_luas'] . ' Ha', 1);




// Tabel Data Jorong
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);




// Header Tabel
$pdf->Cell(15, 7, 'No', 1, 0, 'C');
$pdf->Cell(40, 7, 'Nama Jorong', 1, 0, 'C');
$pdf->Cell(40, 7, 'Kepala Jorong', 1, 0, 'C');
$pdf->Cell(30, 7, 'Luas (Ha)', 1, 0, 'C');
$pdf->Cell(30, 7, 'Jumlah KK', 1, 1, 'C');




// Data Tabel
$pdf->SetFont('helvetica', '', 10);
$no = 1;
foreach ($jorongList as $jorong) {
    $pdf->Cell(15, 7, $no++, 1);
    $pdf->Cell(40, 7, $jorong['nama_jorong'], 1);
    $pdf->Cell(40, 7, $jorong['kepala_jorong'], 1);
    $pdf->Cell(30, 7, $jorong['luas_wilayah'], 1);
    $pdf->Cell(30, 7, $jorong['jumlah_kk'], 1, 1);
}




// Tanda tangan (opsional)
$pdf->Ln(20);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Muara Kiawai, ' . date('d F Y'), 0, 1, 'R');
$pdf->Cell(0, 7, 'Kepala Nagari,', 0, 1, 'R');
$pdf->Ln(20);
$pdf->Cell(0, 7, '( ........................ )', 0, 1, 'R');




// Keluarkan PDF
$pdf->Output('Laporan_Jorong_' . date('Y-m-d') . '.pdf', 'I');