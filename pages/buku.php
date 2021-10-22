<div class="row">
    <div class="col-md-12">
        <div class="container text-center">
            <h1>Data Buku</h1>
        </div>
        <div class="container">
            <form id="form" onsubmit="event.preventDefault()" action="POST">
                <input type="hidden" name="kode_buku" style="margin-top:2rem" class="m-1 form-control">
                <input type="text" name="judul" placeholder="Judul" style="margin-top:2rem" class="m-1 form-control">
                <input type="text" name="penerbit" placeholder="Penerbit" style="margin-top:2rem" class="m-1 form-control">
                <input type="text" name="pengarang" placeholder="Pengarang" style="margin-top:2rem" class="m-1 form-control">
                <button id="insert" class="btn btn-success" style="margin-top:2rem">Simpan</button>
            </form>
        </div>
        <table class="table table-responsive table-stripped">
            <thead>
                <th>Kode Buku</th>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th>Aksi</th>
            </thead>
            <tbody id="container-data"></tbody>
        </table>
    </div>
</div>
<?php $link = "buku"; ?>
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
                                <td>${item.kode_buku}</td>
                                <td>${item.judul}</td>
                                <td>${item.pengarang}</td>
                                <td>${item.penerbit}</td>
                                <td>
                                    <button class="btn btn-danger" onclick="deletedata(${item.kode_buku})">
                                        Hapus
                                    </button>
                                    <button class="btn btn-warning" onclick="fillform(${item.kode_buku})">
                                        Edit
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
            if ($("input[name=kode_buku]").val() == "") {
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

    function fillform(id) {
        let data = new FormData;
        data.append("kode_buku", id);
        fetch('<?= url("api/$link/view") ?>', {
            "body": data,
            "method": "POST",
        }).then(res => res.json()).then(response => {
            if (response.success == false) alert(response.message)
            else {
                $("input[name=kode_buku]").val(response.data.kode_buku);
                $("input[name=judul]").val(response.data.judul);
                $("input[name=penerbit]").val(response.data.penerbit);
                $("input[name=pengarang]").val(response.data.pengarang);
            }
        });
    }

    function deletedata(id) {
        let data = new FormData;
        data.append("kode_buku", id);
        fetch('<?= url("api/$link/delete") ?>', {
            "body": data,
            "method": "POST",
        }).then(res => res.json()).then(res => {
            alert(res.message);
            rebuild();
        });
    }
</script>