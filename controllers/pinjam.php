<?php

class Pinjam extends Controller
{
    public $table = 'pinjam';
    public $primary_key = 'id';
    public $columns = ["id", "nrp", "kode_buku", "tgl_pinjam"];

    public function actionIndex()
    {
        $response = $this->ExecQuery("select id, tgl_pinjam, anggota.nama, buku.judul from $this->table, anggota, buku where buku.kode_buku = $this->table.kode_buku and anggota.nrp = $this->table.nrp");
        response_api($response);
    }
}
