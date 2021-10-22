<?php
require_once 'config/function_helper.php';
require_once 'config/db.php';

$query = new QueryBuilder($db);
$anggota = (object)($query->ExecQuery("select * from anggota", [])['data']);
$buku = (object)($query->ExecQuery("select * from buku", [])['data']);
?>

<div class="row">
    <div class="col-md-12">
        <div class="container text-center">
            <h1>Data Pinjam</h1>
        </div>
        <div class="container">
            <form id="form" onsubmit="event.preventDefault()" action="POST">
                <input type="hidden" name="id" style="margin-top:2rem" class="m-1 form-control">
                <select name="nrp" placeholder="NRP" style="margin-top:2rem" class="m-1 form-control">
                    <?php foreach ($anggota as $item) : ?>
                        <option value="<?= $item->nrp ?>"><?= $item->nama ?></option>
                    <?php endforeach ?>
                </select>
                <select name="kode_buku" style="margin-top:2rem" class="m-1 form-control">
                    <?php foreach ($buku as $item) : ?>
                        <option value="<?= $item->kode_buku ?>"><?= $item->judul ?></option>
                    <?php endforeach ?>
                </select>
                <input type="date" name="tgl_pinjam" placeholder="Tanggal" style="margin-top:2rem" class="m-1 form-control">
                <button id="insert" class="btn btn-success" style="margin-top:2rem">Simpan</button>
            </form>
        </div>
        <table class="table table-responsive table-stripped">
            <thead>
                <th>ID</th>
                <th>Nrp</th>
                <th>Kode Buku</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </thead>
            <tbody id="container-data"></tbody>
        </table>
    </div>
</div>

<?php $link = "pinjam"; ?>
<script>
    let container = $("#container-data");
    let insert = $("#insert");
    let form = $("#form");

    function rebuild() {
        fetch('<?= url("api/$link") ?>').then(res => res.json()).then(res => {
            if (typeof res.data != "object") alert("Data tidak ditemukan");
            else {
                container.html("");
                if (res.data == null || res.data.length == 0) container.append(`<tr><td colspan="5" style="text-align: center">Data tidak ada</td></tr>`);
                else {
                    res.data.forEach(item => {
                        container.append(`
                            <tr>
                                <td>${item.id}</td>
                                <td>${item.nama}</td>
                                <td>${item.judul}</td>
                                <td>${item.tgl_pinjam}</td>
                                <td>
                                    <button class="btn btn-danger" onclick="deletedata(${item.id})">
                                        Hapus
                                    </button>
                                </td>
                            </tr>;
                        `)
                    });
                }
            }
        });
    }

    $(document).ready(function() {
        rebuild();
        insertfunction();
    });


    function buildForm(data) {
        let form = new FormData;
        data.map(item => {

            form.append(item.name, item.value);
        });

        return form;
    }


    function insertfunction() {
        insert.on('click', () => {
            let data = buildForm(form.serializeArray());
            if ($("input[name=id]").val() == "") {
                fetch('<?= url("api/$link/create") ?>', {
                    method: "POST",
                    body: data,
                }).then(res => res.json()).then(res => {
                    if (res.success == false) alert(res.message);
                    else {
                        rebuild();
                        form[0].reset();
                    }
                });
            } else {
                fetch('<?= url("api/$link/update") ?>', {
                    method: "POST",
                    body: data,
                }).then(res => res.json()).then(res => {
                    if (res.success == false) alert(res.message);
                    else {
                        rebuild();
                        form[0].reset();
                    }
                });
            }
        });
    }

    function deletedata(id) {
        let data = new FormData;
        data.append("id", id);
        fetch('<?= url("api/$link/delete") ?>', {
            "body": data,
            "method": "POST",
        }).then(res => res.json()).then(res => {
            alert(res.message);
            rebuild();
        });
    }
</script>