<div class="row">
    <div class="col-md-12">
        <div class="container text-center">
            <h1>Data Anggota</h1>
        </div>
        <div class="container">
            <form id="form" onsubmit="event.preventDefault()" action="POST">
                <input type="number" name="nrp" placeholder="NRP" style="margin-top:2rem" class="m-1 form-control">
                <input type="text" name="nama" placeholder="Nama" style="margin-top:2rem" class="m-1 form-control">
                <input type="date" name="tgl_lahir" placeholder="Tanggal Lahir" style="margin-top:2rem" class="m-1 form-control">
                <input type="alamat" name="alamat" placeholder="Alamat" style="margin-top:2rem" class="m-1 form-control">
                <input type="no_hp" name="no_hp" placeholder="No HP" style="margin-top:2rem" class="m-1 form-control">
                <button id="insert" class="btn btn-success" style="margin-top:2rem">Simpan</button>
            </form>
        </div>
        <table class="table table-responsive table-stripped">
            <thead>
                <th>NRP</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Aksi</th>
            </thead>
            <tbody id="container-data"></tbody>
        </table>
    </div>
</div>

<?php $link = "anggota"; ?>
<script>
    let container = $("#container-data");
    let insert = $("#insert");
    let form = $("#form");

    function rebuild() {
        fetch('<?= url("api/$link") ?>').then(res => res.json()).then(res => {
            if (typeof res.data != "object") alert("Data tidak ditemukan");
            else {
                container.html("");
                if (res.data == null || res.data.length == 0) container.append(`<tr><td colspan="6" style="text-align: center">Data tidak ada</td></tr>`);
                else {
                    res.data.forEach(item => {

                        container.append(`
                            <tr>
                                <td>${item.nrp}</td>
                                <td>${item.nama}</td>
                                <td>${item.tgl_lahir}</td>
                                <td>${item.alamat}</td>
                                <td>${item.no_hp}</td>
                                <td>
                                    <button class="btn btn-danger" onclick="deletedata(${item.nrp})">
                                        Hapus
                                    </button>
                                    <button class="btn btn-warning" onclick="fillform(${item.nrp})">
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
            if ($("input[name=nrp]").val() == "") {
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
        data.append("nrp", id);
        fetch('<?= url("api/$link/view") ?>', {
            "body": data,
            "method": "POST",
        }).then(res => res.json()).then(response => {
            if (response.success == false) alert(response.message)
            else {
                console.log(response.data);
                $("input[name=nrp]").val(response.data.nrp);
                $("input[name=nama]").val(response.data.nama);
                $("input[name=tgl_lahir]").val(response.data.tgl_lahir);
                $("input[name=alamat]").val(response.data.alamat);
                $("input[name=no_hp]").val(response.data.no_hp);
            }
        });
    }

    function deletedata(id) {
        let data = new FormData;
        data.append("nrp", id);
        fetch('<?= url("api/$link/delete") ?>', {
            "body": data,
            "method": "POST",
        }).then(res => res.json()).then(res => {
            alert(res.message);
            rebuild();
        });
    }
</script>