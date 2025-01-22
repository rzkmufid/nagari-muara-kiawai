<?php
require_once __DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php';
require_once __DIR__ . '/../../models/User.php';


// Inisialisasi model
$userModel = new User();


// Ambil parameter dari GET
$jenisLaporan = $_GET['jenis_laporan'] ?? 'pdf';
$userId = $_GET['user'] ?? null;


// Filter data sesuai parameter
if ($userId) {
    $userList = array_filter($userModel->getAllUsers(), function($user) use ($userId) {
        return $user['id'] == $userId;
    });
} else {
    $userList = $userModel->getAllUsers();
}


// Hitung statistik
$statistik = [
    'total_user' => count($userList),
    'total_admin' => count(array_filter($userList, function($user) { return $user['role'] == 'admin'; })),
    'total_user1' => count(array_filter($userList, function($user) { return $user['role'] == 'user1'; })),
    'total_user2' => count(array_filter($userList, function($user) { return $user['role'] == 'user2'; }))
];


// Buat dokumen PDF baru
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// Informasi dokumen
$pdf->SetCreator('Nagari Muara Kiawai');
$pdf->SetAuthor('Pemerintah Nagari');
$pdf->SetTitle('Laporan Manajemen User');
$pdf->SetSubject('Laporan User');


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
$pdf->Cell(0, 7, 'LAPORAN MANAJEMEN USER', 0, 1, 'C');
$pdf->Cell(0, 7, 'TAHUN ' . date('Y'), 0, 1, 'C');


// Garis pemisah
$pdf->Line(15, 40, 195, 40);


// Statistik
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(10);
$pdf->Cell(70, 7, 'Total User', 1);
$pdf->Cell(0, 7, $statistik['total_user'], 1);
$pdf->Ln();
$pdf->Cell(70, 7, 'Total Admin', 1);
$pdf->Cell(0, 7, $statistik['total_admin'], 1);
$pdf->Ln();
$pdf->Cell(70, 7, 'Total User Level 1', 1);
$pdf->Cell(0, 7, $statistik['total_user1'], 1);
$pdf->Ln();
$pdf->Cell(70, 7, 'Total User Level 2', 1);
$pdf->Cell(0, 7, $statistik['total_user2'], 1);


// Tabel Data User
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);


// Header Tabel
$pdf->Cell(15, 7, 'ID', 1, 0, 'C');
$pdf->Cell(40, 7, 'Username', 1, 0, 'C');
$pdf->Cell(30, 7, 'Role', 1, 0, 'C');
$pdf->Cell(0, 7, 'Terakhir Login', 1, 1, 'C');


// Data Tabel
$pdf->SetFont('helvetica', '', 10);
foreach ($userList as $user) {
    $pdf->Cell(15, 7, $user['id'], 1);
    $pdf->Cell(40, 7, $user['username'], 1);
    $pdf->Cell(30, 7, $user['role'], 1);
    $pdf->Cell(0, 7, $user['last_login'] ?? 'Belum Pernah Login', 1, 1);
}


// Tanda tangan (opsional)
$pdf->Ln(20);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Muara Kiawai, ' . date('d F Y'), 0, 1, 'R');
$pdf->Cell(0, 7, 'Kepala Nagari,', 0, 1, 'R');
$pdf->Ln(20);
$pdf->Cell(0, 7, '( ........................ )', 0, 1, 'R');


// Keluarkan PDF
$pdf->Output('Laporan_User_' . date('Y-m-d') . '.pdf', 'I');
