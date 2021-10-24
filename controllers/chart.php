<?php

class Chart extends Controller
{
    public function actionIndex()
    {
        /**
         * array 1 = buku
         * array 2 = anggota
         */
        try {
            $response['message'] = "Data berhasil didapatkan";
            $response['data'][] = $this->ExecQuery("select count(kode_buku) as count from buku limit 1", [], false)->count;
            $response['data'][] = $this->ExecQuery("select count(nrp) as count from anggota limit 1", [], false)->count;

            response_api($response);
        } catch (Exception $e) {
            response_api(["success" => false, "message" => "Telah Terjadi Kesalahan: " . $e]);
        }
    }

    public function actionView($post)
    {
        response_api(["success" => false, "message" => "Not Found"]);
    }

    public function actionCreate($post)
    {
        response_api(["success" => false, "message" => "Not Found"]);
    }

    public function actionUpdate($post)
    {
        response_api(["success" => false, "message" => "Not Found"]);
    }

    public function actionDelete($post)
    {
        response_api(["success" => false, "message" => "Not Found"]);
    }
}
