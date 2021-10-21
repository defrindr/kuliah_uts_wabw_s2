<?php

class Pinjam extends Controller
{
    public $table = 'pinjam';
    public $primary_key = 'id';
    public $columns = ["nrp", "kode_buku", "tgl_pinjam"];
}
