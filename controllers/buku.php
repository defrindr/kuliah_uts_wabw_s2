<?php

class Buku extends Controller
{
    public $table = 'buku';
    public $primary_key = 'kode_buku';
    public $columns = ["kode_buku", "judul", "pengarang", "penerbit"];
}
