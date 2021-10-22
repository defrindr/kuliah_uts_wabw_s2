<?php

class QueryBuilder
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
            if ($post[$key] == "" && $key != $this->primary_key) response_api(['success' => false, 'message' => "Field '$key' tidak boleh kosong"]);
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
}
