<?php
require_once __DIR__ . '/../config/database.php';

class PurchaseOrderController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"purchase_order\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"purchase_order\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"purchase_order\" (\"no_po\", \"pr_id\", \"vendor_id\", \"date_of_po\", \"expected_date\", \"total_amount_po\", \"ppn_amount\", \"grand_total_po\", \"term_of_payment\", \"status\") VALUES (:no_po, :pr_id, :vendor_id, :date_of_po, :expected_date, :total_amount_po, :ppn_amount, :grand_total_po, :term_of_payment, :status) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_po'] = $input['no_po'] ?? null;
        $params[':pr_id'] = $input['pr_id'] ?? null;
        $params[':vendor_id'] = $input['vendor_id'] ?? null;
        $params[':date_of_po'] = $input['date_of_po'] ?? null;
        $params[':expected_date'] = $input['expected_date'] ?? null;
        $params[':total_amount_po'] = $input['total_amount_po'] ?? null;
        $params[':ppn_amount'] = $input['ppn_amount'] ?? null;
        $params[':grand_total_po'] = $input['grand_total_po'] ?? null;
        $params[':term_of_payment'] = $input['term_of_payment'] ?? null;
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
        $sql = "UPDATE \"purchase_order\" SET "\"no_po\" = :no_po", "\"pr_id\" = :pr_id", "\"vendor_id\" = :vendor_id", "\"date_of_po\" = :date_of_po", "\"expected_date\" = :expected_date", "\"total_amount_po\" = :total_amount_po", "\"ppn_amount\" = :ppn_amount", "\"grand_total_po\" = :grand_total_po", "\"term_of_payment\" = :term_of_payment", "\"status\" = :status" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_po'] = array_key_exists('no_po', $input) ? $input['no_po'] : null;
        $params[':pr_id'] = array_key_exists('pr_id', $input) ? $input['pr_id'] : null;
        $params[':vendor_id'] = array_key_exists('vendor_id', $input) ? $input['vendor_id'] : null;
        $params[':date_of_po'] = array_key_exists('date_of_po', $input) ? $input['date_of_po'] : null;
        $params[':expected_date'] = array_key_exists('expected_date', $input) ? $input['expected_date'] : null;
        $params[':total_amount_po'] = array_key_exists('total_amount_po', $input) ? $input['total_amount_po'] : null;
        $params[':ppn_amount'] = array_key_exists('ppn_amount', $input) ? $input['ppn_amount'] : null;
        $params[':grand_total_po'] = array_key_exists('grand_total_po', $input) ? $input['grand_total_po'] : null;
        $params[':term_of_payment'] = array_key_exists('term_of_payment', $input) ? $input['term_of_payment'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"purchase_order\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
