<?php
require_once __DIR__ . '/../config/database.php';

class PoDetailController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"po_detail\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"po_detail\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"po_detail\" (\"po_id\", \"item_id\", \"volume_konversi_tertinggi\", \"harga_satuan\", \"total_amount\") VALUES (:po_id, :item_id, :volume_konversi_tertinggi, :harga_satuan, :total_amount) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':po_id'] = $input['po_id'] ?? null;
        $params[':item_id'] = $input['item_id'] ?? null;
        $params[':volume_konversi_tertinggi'] = $input['volume_konversi_tertinggi'] ?? null;
        $params[':harga_satuan'] = $input['harga_satuan'] ?? null;
        $params[':total_amount'] = $input['total_amount'] ?? null;
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
        $sql = "UPDATE \"po_detail\" SET "\"po_id\" = :po_id", "\"item_id\" = :item_id", "\"volume_konversi_tertinggi\" = :volume_konversi_tertinggi", "\"harga_satuan\" = :harga_satuan", "\"total_amount\" = :total_amount" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':po_id'] = array_key_exists('po_id', $input) ? $input['po_id'] : null;
        $params[':item_id'] = array_key_exists('item_id', $input) ? $input['item_id'] : null;
        $params[':volume_konversi_tertinggi'] = array_key_exists('volume_konversi_tertinggi', $input) ? $input['volume_konversi_tertinggi'] : null;
        $params[':harga_satuan'] = array_key_exists('harga_satuan', $input) ? $input['harga_satuan'] : null;
        $params[':total_amount'] = array_key_exists('total_amount', $input) ? $input['total_amount'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"po_detail\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
