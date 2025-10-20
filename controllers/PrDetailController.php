<?php
require_once __DIR__ . '/../config/database.php';

class PrDetailController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"pr_detail\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"pr_detail\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"pr_detail\" (\"pr_id\", \"item_id\", \"volume_qty_terkecil\", \"quantity\", \"approved_qty\", \"rejected_qty\", \"status_item\") VALUES (:pr_id, :item_id, :volume_qty_terkecil, :quantity, :approved_qty, :rejected_qty, :status_item) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':pr_id'] = $input['pr_id'] ?? null;
        $params[':item_id'] = $input['item_id'] ?? null;
        $params[':volume_qty_terkecil'] = $input['volume_qty_terkecil'] ?? null;
        $params[':quantity'] = $input['quantity'] ?? null;
        $params[':approved_qty'] = $input['approved_qty'] ?? null;
        $params[':rejected_qty'] = $input['rejected_qty'] ?? null;
        $params[':status_item'] = $input['status_item'] ?? null;
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
        $sql = "UPDATE \"pr_detail\" SET "\"pr_id\" = :pr_id", "\"item_id\" = :item_id", "\"volume_qty_terkecil\" = :volume_qty_terkecil", "\"quantity\" = :quantity", "\"approved_qty\" = :approved_qty", "\"rejected_qty\" = :rejected_qty", "\"status_item\" = :status_item" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':pr_id'] = array_key_exists('pr_id', $input) ? $input['pr_id'] : null;
        $params[':item_id'] = array_key_exists('item_id', $input) ? $input['item_id'] : null;
        $params[':volume_qty_terkecil'] = array_key_exists('volume_qty_terkecil', $input) ? $input['volume_qty_terkecil'] : null;
        $params[':quantity'] = array_key_exists('quantity', $input) ? $input['quantity'] : null;
        $params[':approved_qty'] = array_key_exists('approved_qty', $input) ? $input['approved_qty'] : null;
        $params[':rejected_qty'] = array_key_exists('rejected_qty', $input) ? $input['rejected_qty'] : null;
        $params[':status_item'] = array_key_exists('status_item', $input) ? $input['status_item'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"pr_detail\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
