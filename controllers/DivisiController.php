<?php
require_once __DIR__ . '/../config/database.php';

class DivisiController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"divisi\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"divisi\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"divisi\" (\"no_id\", \"kode\", \"nama_divisi\", \"departemen_id\") VALUES (:no_id, :kode, :nama_divisi, :departemen_id) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_id'] = $input['no_id'] ?? null;
        $params[':kode'] = $input['kode'] ?? null;
        $params[':nama_divisi'] = $input['nama_divisi'] ?? null;
        $params[':departemen_id'] = $input['departemen_id'] ?? null;
        try{
            $stmt->execute($params);
            $id = $stmt->fetchColumn();
            echo json_encode(['status'=>'ok','id'=>$id]);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }

    public function update($id){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE \"divisi\" SET "\"no_id\" = :no_id", "\"kode\" = :kode", "\"nama_divisi\" = :nama_divisi", "\"departemen_id\" = :departemen_id" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_id'] = array_key_exists('no_id', $input) ? $input['no_id'] : null;
        $params[':kode'] = array_key_exists('kode', $input) ? $input['kode'] : null;
        $params[':nama_divisi'] = array_key_exists('nama_divisi', $input) ? $input['nama_divisi'] : null;
        $params[':departemen_id'] = array_key_exists('departemen_id', $input) ? $input['departemen_id'] : null;
        $params[':id'] = $id;
        try{
            $stmt->execute($params);
            echo json_encode(['status'=>'ok']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }

    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM \"divisi\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
