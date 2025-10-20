<?php
require_once __DIR__ . '/../config/database.php';

class PurchaseRequestController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"purchase_request\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"purchase_request\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"purchase_request\" (\"no_pr\", \"tanggal_request\", \"user_request_id\", \"unit_id\", \"expected_date_delivery\", \"group_item_id\", \"location_gudang_id\", \"status\", \"head_approve_user_id\", \"tanggal_approve\") VALUES (:no_pr, :tanggal_request, :user_request_id, :unit_id, :expected_date_delivery, :group_item_id, :location_gudang_id, :status, :head_approve_user_id, :tanggal_approve) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_pr'] = $input['no_pr'] ?? null;
        $params[':tanggal_request'] = $input['tanggal_request'] ?? null;
        $params[':user_request_id'] = $input['user_request_id'] ?? null;
        $params[':unit_id'] = $input['unit_id'] ?? null;
        $params[':expected_date_delivery'] = $input['expected_date_delivery'] ?? null;
        $params[':group_item_id'] = $input['group_item_id'] ?? null;
        $params[':location_gudang_id'] = $input['location_gudang_id'] ?? null;
        $params[':status'] = $input['status'] ?? null;
        $params[':head_approve_user_id'] = $input['head_approve_user_id'] ?? null;
        $params[':tanggal_approve'] = $input['tanggal_approve'] ?? null;
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
        $sql = "UPDATE \"purchase_request\" SET "\"no_pr\" = :no_pr", "\"tanggal_request\" = :tanggal_request", "\"user_request_id\" = :user_request_id", "\"unit_id\" = :unit_id", "\"expected_date_delivery\" = :expected_date_delivery", "\"group_item_id\" = :group_item_id", "\"location_gudang_id\" = :location_gudang_id", "\"status\" = :status", "\"head_approve_user_id\" = :head_approve_user_id", "\"tanggal_approve\" = :tanggal_approve" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_pr'] = array_key_exists('no_pr', $input) ? $input['no_pr'] : null;
        $params[':tanggal_request'] = array_key_exists('tanggal_request', $input) ? $input['tanggal_request'] : null;
        $params[':user_request_id'] = array_key_exists('user_request_id', $input) ? $input['user_request_id'] : null;
        $params[':unit_id'] = array_key_exists('unit_id', $input) ? $input['unit_id'] : null;
        $params[':expected_date_delivery'] = array_key_exists('expected_date_delivery', $input) ? $input['expected_date_delivery'] : null;
        $params[':group_item_id'] = array_key_exists('group_item_id', $input) ? $input['group_item_id'] : null;
        $params[':location_gudang_id'] = array_key_exists('location_gudang_id', $input) ? $input['location_gudang_id'] : null;
        $params[':status'] = array_key_exists('status', $input) ? $input['status'] : null;
        $params[':head_approve_user_id'] = array_key_exists('head_approve_user_id', $input) ? $input['head_approve_user_id'] : null;
        $params[':tanggal_approve'] = array_key_exists('tanggal_approve', $input) ? $input['tanggal_approve'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"purchase_request\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
