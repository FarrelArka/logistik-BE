<?php
require_once __DIR__ . '/../config/database.php';

class ApPaymentHeaderController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"ap_payment_header\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"ap_payment_header\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"ap_payment_header\" (\"no_apc\", \"tanggal_payment\", \"vendor_id\", \"cash_bank_transfer\", \"no_rekening_bank\", \"nama_rekening_bank\", \"total_payment\", \"status\") VALUES (:no_apc, :tanggal_payment, :vendor_id, :cash_bank_transfer, :no_rekening_bank, :nama_rekening_bank, :total_payment, :status) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_apc'] = $input['no_apc'] ?? null;
        $params[':tanggal_payment'] = $input['tanggal_payment'] ?? null;
        $params[':vendor_id'] = $input['vendor_id'] ?? null;
        $params[':cash_bank_transfer'] = $input['cash_bank_transfer'] ?? null;
        $params[':no_rekening_bank'] = $input['no_rekening_bank'] ?? null;
        $params[':nama_rekening_bank'] = $input['nama_rekening_bank'] ?? null;
        $params[':total_payment'] = $input['total_payment'] ?? null;
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
        $sql = "UPDATE \"ap_payment_header\" SET "\"no_apc\" = :no_apc", "\"tanggal_payment\" = :tanggal_payment", "\"vendor_id\" = :vendor_id", "\"cash_bank_transfer\" = :cash_bank_transfer", "\"no_rekening_bank\" = :no_rekening_bank", "\"nama_rekening_bank\" = :nama_rekening_bank", "\"total_payment\" = :total_payment", "\"status\" = :status" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_apc'] = array_key_exists('no_apc', $input) ? $input['no_apc'] : null;
        $params[':tanggal_payment'] = array_key_exists('tanggal_payment', $input) ? $input['tanggal_payment'] : null;
        $params[':vendor_id'] = array_key_exists('vendor_id', $input) ? $input['vendor_id'] : null;
        $params[':cash_bank_transfer'] = array_key_exists('cash_bank_transfer', $input) ? $input['cash_bank_transfer'] : null;
        $params[':no_rekening_bank'] = array_key_exists('no_rekening_bank', $input) ? $input['no_rekening_bank'] : null;
        $params[':nama_rekening_bank'] = array_key_exists('nama_rekening_bank', $input) ? $input['nama_rekening_bank'] : null;
        $params[':total_payment'] = array_key_exists('total_payment', $input) ? $input['total_payment'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"ap_payment_header\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
