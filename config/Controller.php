<?php
class Controller
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function ExecQuery($query, $binding = [])
    {
        $model = $this->db->prepare($query);
        $model->setFetchMode(PDO::FETCH_OBJ);
        $model->execute($binding);
        $total_data = $model->rowCount();

        $response = [];
        $response['message'] = 'Data berhasil didapatkan';

        if ($total_data) {
            while ($row = $model->fetch()) {
                if (strpos($query, "limit 1") !== false) {
                    $response['data'] = $row;
                } else {
                    $response['data'][] = $row;
                }
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Data kosong';
        }

        return $response;
    }


    public function insert($query, $binding)
    {
        $response['message'] = "Data berhasil ditambahkan";
        try {
            $model = $this->db->prepare($query);
            $model->setFetchMode(PDO::FETCH_OBJ);
            $model->execute($binding);
        } catch (\Throwable $th) {
            $response['success'] = false;
            $response['message'] = "Data gagal ditambahkan: " . $th->getMessage();
        }

        return $response;
    }

    public function delete($query, $binding = [])
    {
        $model = $this->db->prepare($query);
        $model->setFetchMode(PDO::FETCH_OBJ);
        if ($model->execute($binding)) {
            $response['message'] = "Data berhasil dihapus";
        } else {
            $response['success'] = false;
            $response['message'] = "Data gagal dihapus";
        }

        return $response;
    }

    public function assignData($keys, $post)
    {
        $params = [];
        foreach ($keys as $key) {
            if (isset($post[$key]) == false) response_api(['success' => false, 'message' => "Field '$key' tidak boleh kosong"]);
            $params[":" . $key] = $post[$key];
        }
        return $params;
    }

    public function createInsertQuery($table, $arrays)
    {
        $template_1 = implode(", ", $arrays);
        foreach ($arrays as $key => $item) $arrays[$key] = ":$item";
        $template_2 = implode(", ", $arrays);
        $query = "INSERT INTO $table ($template_1) values ($template_2)";
        return $query;
    }

    public function createUpdateQuery($table, $arrays, $primary)
    {
        foreach ($arrays as $key => $item) $arrays[$key] = "$item=:$item";
        $template_1 = implode(", ", $arrays);
        $query = "UPDATE $table SET $template_1 where $primary=:$primary";
        return $query;
    }


    public function actionIndex()
    {
        $response = $this->ExecQuery("select * from $this->table");
        response_api($response);
    }

    public function actionView($id)
    {
        try {
            $response = $this->ExecQuery("select * from $this->table where {$this->primary_key}=:{$this->primary_key}", [$this->primary_key => $id]);
            response_api($response);
        } catch (\Exception $e) {
            response_api(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function actionCreate($post)
    {
        try {
            $keys = $this->columns;
            unset($keys[array_search($this->primary_key, $this->columns)]);
            $params = $this->assignData($keys, $post);
            $query = $this->createInsertQuery($this->table, $keys);
            $response = $this->insert($query, $params);
            response_api($response);
        } catch (\Exception $e) {
            response_api(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function actionUpdate($post)
    {
        try {
            $keys = [$this->primary_key];
            $kode = $this->assignData($keys, $post);
            $data = $this->ExecQuery("select * from $this->table where {$this->primary_key}=:{$this->primary_key}", $kode);

            $keys = $this->columns;
            unset($keys[array_search($this->primary_key, $this->columns)]);
            $params = $this->assignData($this->columns, $post);
            if ($data['message'] == "Data kosong") {
                response_api(["success" => false, "message" => "data tidak ditemukan"]);
            }

            $query = $this->createUpdateQuery($this->table, $keys, $this->primary_key);
            $response = $this->insert($query, $params);
            response_api($response);
        } catch (\Exception $e) {
            response_api(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function actionDelete($post)
    {
        try {
            $keys = [$this->primary_key];
            $kode = $this->assignData($keys, $post);
            $response = $this->delete("delete from $this->table where {$this->primary_key}=:{$this->primary_key}", $kode);
            response_api($response);
        } catch (\Exception $e) {
            response_api(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
