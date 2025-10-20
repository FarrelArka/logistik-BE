<?php
require_once __DIR__ . '/../config/database.php';

class GoodReceiptController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"good_receipt\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"good_receipt\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"good_receipt\" (\"no_gr\", \"tanggal_gr\", \"po_id\", \"unit_id\", \"departemen_id\", \"group_category_id\", \"inventory_category\", \"total_amount_gr\", \"status\") VALUES (:no_gr, :tanggal_gr, :po_id, :unit_id, :departemen_id, :group_category_id, :inventory_category, :total_amount_gr, :status) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_gr'] = $input['no_gr'] ?? null;
        $params[':tanggal_gr'] = $input['tanggal_gr'] ?? null;
        $params[':po_id'] = $input['po_id'] ?? null;
        $params[':unit_id'] = $input['unit_id'] ?? null;
        $params[':departemen_id'] = $input['departemen_id'] ?? null;
        $params[':group_category_id'] = $input['group_category_id'] ?? null;
        $params[':inventory_category'] = $input['inventory_category'] ?? null;
        $params[':total_amount_gr'] = $input['total_amount_gr'] ?? null;
        $params[':status'] = $input['status'] ?? null;
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
        $sql = "UPDATE \"good_receipt\" SET "\"no_gr\" = :no_gr", "\"tanggal_gr\" = :tanggal_gr", "\"po_id\" = :po_id", "\"unit_id\" = :unit_id", "\"departemen_id\" = :departemen_id", "\"group_category_id\" = :group_category_id", "\"inventory_category\" = :inventory_category", "\"total_amount_gr\" = :total_amount_gr", "\"status\" = :status" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_gr'] = array_key_exists('no_gr', $input) ? $input['no_gr'] : null;
        $params[':tanggal_gr'] = array_key_exists('tanggal_gr', $input) ? $input['tanggal_gr'] : null;
        $params[':po_id'] = array_key_exists('po_id', $input) ? $input['po_id'] : null;
        $params[':unit_id'] = array_key_exists('unit_id', $input) ? $input['unit_id'] : null;
        $params[':departemen_id'] = array_key_exists('departemen_id', $input) ? $input['departemen_id'] : null;
        $params[':group_category_id'] = array_key_exists('group_category_id', $input) ? $input['group_category_id'] : null;
        $params[':inventory_category'] = array_key_exists('inventory_category', $input) ? $input['inventory_category'] : null;
        $params[':total_amount_gr'] = array_key_exists('total_amount_gr', $input) ? $input['total_amount_gr'] : null;
        $params[':status'] = array_key_exists('status', $input) ? $input['status'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"good_receipt\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
