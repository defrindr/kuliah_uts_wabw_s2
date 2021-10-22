<?php
class Controller extends QueryBuilder
{
    public function actionIndex()
    {
        $response = $this->ExecQuery("select * from $this->table");
        response_api($response);
    }

    public function actionView($post)
    {
        try {
            $keys = [$this->primary_key];
            $kode = $this->assignData($keys, $post);
            $response = $this->ExecQuery("select * from $this->table where {$this->primary_key}=:{$this->primary_key} limit 1", $kode);
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
