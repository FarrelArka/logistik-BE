<?php
require_once __DIR__ . '/../config/database.php';

class GrDetailController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"gr_detail\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"gr_detail\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"gr_detail\" (\"gr_id\", \"item_id\", \"volume_po\", \"volume_actual_receipt\", \"variance_volume\", \"harga_satuan\", \"total_amount\") VALUES (:gr_id, :item_id, :volume_po, :volume_actual_receipt, :variance_volume, :harga_satuan, :total_amount) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':gr_id'] = $input['gr_id'] ?? null;
        $params[':item_id'] = $input['item_id'] ?? null;
        $params[':volume_po'] = $input['volume_po'] ?? null;
        $params[':volume_actual_receipt'] = $input['volume_actual_receipt'] ?? null;
        $params[':variance_volume'] = $input['variance_volume'] ?? null;
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
        $sql = "UPDATE \"gr_detail\" SET "\"gr_id\" = :gr_id", "\"item_id\" = :item_id", "\"volume_po\" = :volume_po", "\"volume_actual_receipt\" = :volume_actual_receipt", "\"variance_volume\" = :variance_volume", "\"harga_satuan\" = :harga_satuan", "\"total_amount\" = :total_amount" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':gr_id'] = array_key_exists('gr_id', $input) ? $input['gr_id'] : null;
        $params[':item_id'] = array_key_exists('item_id', $input) ? $input['item_id'] : null;
        $params[':volume_po'] = array_key_exists('volume_po', $input) ? $input['volume_po'] : null;
        $params[':volume_actual_receipt'] = array_key_exists('volume_actual_receipt', $input) ? $input['volume_actual_receipt'] : null;
        $params[':variance_volume'] = array_key_exists('variance_volume', $input) ? $input['variance_volume'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"gr_detail\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
