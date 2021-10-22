<?php

class Anggota extends Controller
{
    public $table = 'anggota';
    public $primary_key = 'nrp';
    public $columns = ["nrp", "nama", "tgl_lahir", "alamat", "no_hp"];
}
