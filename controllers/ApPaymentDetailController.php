<?php
require_once __DIR__ . '/../config/database.php';

class ApPaymentDetailController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"ap_payment_detail\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"ap_payment_detail\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"ap_payment_detail\" (\"ap_payment_header_id\", \"ap_voucher_id\", \"amount_paid\") VALUES (:ap_payment_header_id, :ap_voucher_id, :amount_paid) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':ap_payment_header_id'] = $input['ap_payment_header_id'] ?? null;
        $params[':ap_voucher_id'] = $input['ap_voucher_id'] ?? null;
        $params[':amount_paid'] = $input['amount_paid'] ?? null;
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
        $sql = "UPDATE \"ap_payment_detail\" SET "\"ap_payment_header_id\" = :ap_payment_header_id", "\"ap_voucher_id\" = :ap_voucher_id", "\"amount_paid\" = :amount_paid" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':ap_payment_header_id'] = array_key_exists('ap_payment_header_id', $input) ? $input['ap_payment_header_id'] : null;
        $params[':ap_voucher_id'] = array_key_exists('ap_voucher_id', $input) ? $input['ap_voucher_id'] : null;
        $params[':amount_paid'] = array_key_exists('amount_paid', $input) ? $input['amount_paid'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"ap_payment_detail\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
