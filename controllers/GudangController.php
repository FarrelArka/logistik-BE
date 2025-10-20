<?php
require_once __DIR__ . '/../config/database.php';

class GudangController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"gudang\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"gudang\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"gudang\" (\"no_gudang\", \"nama_gudang\", \"lokasi_unit\", \"sub_lokasi_mobile\") VALUES (:no_gudang, :nama_gudang, :lokasi_unit, :sub_lokasi_mobile) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_gudang'] = $input['no_gudang'] ?? null;
        $params[':nama_gudang'] = $input['nama_gudang'] ?? null;
        $params[':lokasi_unit'] = $input['lokasi_unit'] ?? null;
        $params[':sub_lokasi_mobile'] = $input['sub_lokasi_mobile'] ?? null;
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
        $sql = "UPDATE \"gudang\" SET "\"no_gudang\" = :no_gudang", "\"nama_gudang\" = :nama_gudang", "\"lokasi_unit\" = :lokasi_unit", "\"sub_lokasi_mobile\" = :sub_lokasi_mobile" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_gudang'] = array_key_exists('no_gudang', $input) ? $input['no_gudang'] : null;
        $params[':nama_gudang'] = array_key_exists('nama_gudang', $input) ? $input['nama_gudang'] : null;
        $params[':lokasi_unit'] = array_key_exists('lokasi_unit', $input) ? $input['lokasi_unit'] : null;
        $params[':sub_lokasi_mobile'] = array_key_exists('sub_lokasi_mobile', $input) ? $input['sub_lokasi_mobile'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"gudang\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
