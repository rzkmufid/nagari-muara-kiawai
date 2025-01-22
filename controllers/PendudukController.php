<?php
require_once '../models/Penduduk.php';


class PendudukController {
    private $pendudukModel;


    public function __construct() {
        $this->pendudukModel = new Penduduk();
    }


    public function tambahPenduduk($data) {
        // Validasi dan tambah penduduk
        return $this->pendudukModel->create($data);
    }


    public function listPenduduk() {
        return $this->pendudukModel->getAll();
    }


    public function getPendudukByPekerjaan($pekerjaan) {
        return $this->pendudukModel->getByPekerjaan($pekerjaan);
    }


    // Metode CRUD lainnya
}