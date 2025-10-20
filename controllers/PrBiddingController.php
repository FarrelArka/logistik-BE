<?php
require_once __DIR__ . '/../config/database.php';

class PrBiddingController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"pr_bidding\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"pr_bidding\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"pr_bidding\" (\"pr_detail_id\", \"vendor_id\", \"harga_satuan\", \"total_amount\", \"term_of_payment\", \"upload_doc_penawaran\", \"is_winner\", \"manager_approve_user_id\", \"tanggal_approve\") VALUES (:pr_detail_id, :vendor_id, :harga_satuan, :total_amount, :term_of_payment, :upload_doc_penawaran, :is_winner, :manager_approve_user_id, :tanggal_approve) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':pr_detail_id'] = $input['pr_detail_id'] ?? null;
        $params[':vendor_id'] = $input['vendor_id'] ?? null;
        $params[':harga_satuan'] = $input['harga_satuan'] ?? null;
        $params[':total_amount'] = $input['total_amount'] ?? null;
        $params[':term_of_payment'] = $input['term_of_payment'] ?? null;
        $params[':upload_doc_penawaran'] = $input['upload_doc_penawaran'] ?? null;
        $params[':is_winner'] = $input['is_winner'] ?? null;
        $params[':manager_approve_user_id'] = $input['manager_approve_user_id'] ?? null;
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
        $sql = "UPDATE \"pr_bidding\" SET "\"pr_detail_id\" = :pr_detail_id", "\"vendor_id\" = :vendor_id", "\"harga_satuan\" = :harga_satuan", "\"total_amount\" = :total_amount", "\"term_of_payment\" = :term_of_payment", "\"upload_doc_penawaran\" = :upload_doc_penawaran", "\"is_winner\" = :is_winner", "\"manager_approve_user_id\" = :manager_approve_user_id", "\"tanggal_approve\" = :tanggal_approve" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':pr_detail_id'] = array_key_exists('pr_detail_id', $input) ? $input['pr_detail_id'] : null;
        $params[':vendor_id'] = array_key_exists('vendor_id', $input) ? $input['vendor_id'] : null;
        $params[':harga_satuan'] = array_key_exists('harga_satuan', $input) ? $input['harga_satuan'] : null;
        $params[':total_amount'] = array_key_exists('total_amount', $input) ? $input['total_amount'] : null;
        $params[':term_of_payment'] = array_key_exists('term_of_payment', $input) ? $input['term_of_payment'] : null;
        $params[':upload_doc_penawaran'] = array_key_exists('upload_doc_penawaran', $input) ? $input['upload_doc_penawaran'] : null;
        $params[':is_winner'] = array_key_exists('is_winner', $input) ? $input['is_winner'] : null;
        $params[':manager_approve_user_id'] = array_key_exists('manager_approve_user_id', $input) ? $input['manager_approve_user_id'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"pr_bidding\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
