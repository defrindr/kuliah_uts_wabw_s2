<div class="row">
    <div class="col-md-12">
        <div class="container text-center">
            <h1>Data Pinjam</h1>
        </div>
        <div class="container">
            <form id="form" onsubmit="event.preventDefault()" action="POST">
                <input type="hidden" name="id" style="margin-top:2rem" class="m-1 form-control">
                <input type="text" name="nrp" placeholder="NRP" style="margin-top:2rem" class="m-1 form-control">
                <input type="text" name="kode_buku" placeholder="KODE BUKU" style="margin-top:2rem" class="m-1 form-control">
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
<script>
    let container = $("#container-data");
    let insert = $("#insert");
    let form = $("#form");
    // let formupdate= $("#formupdate");

    $(document).ready(function() {
        $.ajax({
            "url": '<?= url("api/buku") ?>',
            "method": "GET",
            success: function(response) {
                container.html("");
                response.data.foreach(item => {
                    container.html(response.html);
                })
            }
        });

        insertfunction();
    });

    function insertfunction() {
        insert.on('click', () => {
            let data = form.serialize();
            $.ajax({
                "url": "/buku/create",
                "method": "POST",
                "data": data,
                success: function(response) {
                    // response = await response.json();
                    container.html(response.html);
                }
            });
            form[0].reset();
        });
    }

    function search(event) {
        $.ajax({
            "url": "server.php?action=search&search=" + event.target.value,
            "method": "GET",
            success: function(response) {
                container.html(response.html);
            }
        });
    }

    function update(id) {
        $.ajax({
            "url": "server.php?action=view&id=" + id,
            "method": "GET",
            success: function(response) {

                $("input[name=id]").val(response.data[0].id);
                $("input[name=nrp]").val(response.data[0].nrp);
                $("input[name=nama]").val(response.data[0].nama);
                $("input[name=prodi]").val(response.data[0].prodi);
            }
        });
    }

    function deletedata(id) {
        $.ajax({
            "url": "server.php?action=delete&id=" + id,
            "method": "GET",
            success: function(response) {
                container.html(response.html)
            }
        });
    }
</script>