<?php
require_once __DIR__ . '/../config/database.php';

class ApVoucherController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"ap_voucher\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"ap_voucher\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"ap_voucher\" (\"no_ap_voucher\", \"gr_id\", \"vendor_id\", \"tanggal_ap\", \"total_amount_gr\", \"term_of_payment\", \"due_date\", \"status\") VALUES (:no_ap_voucher, :gr_id, :vendor_id, :tanggal_ap, :total_amount_gr, :term_of_payment, :due_date, :status) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_ap_voucher'] = $input['no_ap_voucher'] ?? null;
        $params[':gr_id'] = $input['gr_id'] ?? null;
        $params[':vendor_id'] = $input['vendor_id'] ?? null;
        $params[':tanggal_ap'] = $input['tanggal_ap'] ?? null;
        $params[':total_amount_gr'] = $input['total_amount_gr'] ?? null;
        $params[':term_of_payment'] = $input['term_of_payment'] ?? null;
        $params[':due_date'] = $input['due_date'] ?? null;
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
        $sql = "UPDATE \"ap_voucher\" SET "\"no_ap_voucher\" = :no_ap_voucher", "\"gr_id\" = :gr_id", "\"vendor_id\" = :vendor_id", "\"tanggal_ap\" = :tanggal_ap", "\"total_amount_gr\" = :total_amount_gr", "\"term_of_payment\" = :term_of_payment", "\"due_date\" = :due_date", "\"status\" = :status" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_ap_voucher'] = array_key_exists('no_ap_voucher', $input) ? $input['no_ap_voucher'] : null;
        $params[':gr_id'] = array_key_exists('gr_id', $input) ? $input['gr_id'] : null;
        $params[':vendor_id'] = array_key_exists('vendor_id', $input) ? $input['vendor_id'] : null;
        $params[':tanggal_ap'] = array_key_exists('tanggal_ap', $input) ? $input['tanggal_ap'] : null;
        $params[':total_amount_gr'] = array_key_exists('total_amount_gr', $input) ? $input['total_amount_gr'] : null;
        $params[':term_of_payment'] = array_key_exists('term_of_payment', $input) ? $input['term_of_payment'] : null;
        $params[':due_date'] = array_key_exists('due_date', $input) ? $input['due_date'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"ap_voucher\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
