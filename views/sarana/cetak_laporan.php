<?php
require_once __DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php';
require_once __DIR__ . '/../../models/Sarana.php';


// Inisialisasi model
$saranaModel = new Sarana();


// Ambil parameter dari GET
$jenisLaporan = $_GET['jenis_laporan'] ?? 'pdf';
$kondisi = $_GET['kondisi'] ?? null;


// Filter data sesuai kondisi
if ($kondisi) {
    $saranaList = array_filter($saranaModel->readAll(), function($sarana) use ($kondisi) {
        return $sarana['kondisi'] == $kondisi;
    });
} else {
    $saranaList = $saranaModel->readAll();
}


// Hitung statistik
$statistik = [
    'total_sarana' => count($saranaList),
    'total_baik' => count(array_filter($saranaList, function($s) { return $s['kondisi'] == 'Baik'; })),
    'total_rusak_ringan' => count(array_filter($saranaList, function($s) { return $s['kondisi'] == 'Rusak Ringan'; })),
    'total_rusak_berat' => count(array_filter($saranaList, function($s) { return $s['kondisi'] == 'Rusak Berat'; }))
];


// Buat dokumen PDF baru
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// Informasi dokumen
$pdf->SetCreator('Nagari Muara Kiawai');
$pdf->SetAuthor('Pemerintah Nagari');
$pdf->SetTitle('Laporan Sarana dan Prasarana');
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
$pdf->Cell(0, 7, 'LAPORAN SARANA DAN PRASARANA', 0, 1, 'C');
$pdf->Cell(0, 7, 'TAHUN ' . date('Y'), 0, 1, 'C');


// Garis pemisah
$pdf->Line(15, 40, 195, 40);


// Statistik
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(10);
$pdf->Cell(70, 7, 'Total Sarana', 1);
$pdf->Cell(0, 7, $statistik['total_sarana'], 1);
$pdf->Ln();
$pdf->Cell(70, 7, 'Sarana Baik', 1);
$pdf->Cell(0, 7, $statistik['total_baik'], 1);
$pdf->Ln();
$pdf->Cell(70, 7, 'Sarana Rusak Ringan', 1);
$pdf->Cell(0, 7, $statistik['total_rusak_ringan'], 1);
$pdf->Ln();
$pdf->Cell(70, 7, 'Sarana Rusak Berat', 1);
$pdf->Cell(0, 7, $statistik['total_rusak_berat'], 1);


// Tabel Data Sarana
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);


// Header Tabel
$pdf->Cell(15, 7, 'ID', 1, 0, 'C');
$pdf->Cell(40, 7, 'Jenis', 1, 0, 'C');
$pdf->Cell(30, 7, 'Jumlah', 1, 0, 'C');
$pdf->Cell(40, 7, 'Kondisi', 1, 0, 'C');
$pdf->Cell(0, 7, 'Keterangan', 1, 1, 'C');


// Data Tabel
$pdf->SetFont('helvetica', '', 10);
foreach ($saranaList as $sarana) {
    $pdf->Cell(15, 7, $sarana['id'], 1);
    $pdf->Cell(40, 7, $sarana['jenis'], 1);
    $pdf->Cell(30, 7, $sarana['jumlah'], 1);
    $pdf->Cell(40, 7, $sarana['kondisi'], 1);
    $pdf->Cell(0, 7, $sarana['keterangan'], 1, 1);
}


// Tanda tangan (opsional)
$pdf->Ln(20);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Muara Kiawai, ' . date('d F Y'), 0, 1, 'R');
$pdf->Cell(0, 7, 'Kepala Nagari,', 0, 1, 'R');
$pdf->Ln(20);
$pdf->Cell(0, 7, '( ........................ )', 0, 1, 'R');


// Keluarkan PDF
$pdf->Output('Laporan_Sarana_' . date('Y-m-d') . '.pdf', 'I');