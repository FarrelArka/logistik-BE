<?php
require_once __DIR__ . '/../config/database.php';

class ApFaktorPengurangController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"ap_faktor_pengurang\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"ap_faktor_pengurang\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"ap_faktor_pengurang\" (\"ap_payment_header_id\", \"jenis_pengurang\", \"jumlah\") VALUES (:ap_payment_header_id, :jenis_pengurang, :jumlah) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':ap_payment_header_id'] = $input['ap_payment_header_id'] ?? null;
        $params[':jenis_pengurang'] = $input['jenis_pengurang'] ?? null;
        $params[':jumlah'] = $input['jumlah'] ?? null;
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
        $sql = "UPDATE \"ap_faktor_pengurang\" SET "\"ap_payment_header_id\" = :ap_payment_header_id", "\"jenis_pengurang\" = :jenis_pengurang", "\"jumlah\" = :jumlah" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':ap_payment_header_id'] = array_key_exists('ap_payment_header_id', $input) ? $input['ap_payment_header_id'] : null;
        $params[':jenis_pengurang'] = array_key_exists('jenis_pengurang', $input) ? $input['jenis_pengurang'] : null;
        $params[':jumlah'] = array_key_exists('jumlah', $input) ? $input['jumlah'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"ap_faktor_pengurang\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
