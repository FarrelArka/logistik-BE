<?php
require_once __DIR__ . '/../config/database.php';

class VendorController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"vendor\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"vendor\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"vendor\" (\"no_vendor\", \"nama_vendor\", \"badan_usaha\", \"alamat_vendor\", \"nib\", \"npwp\", \"pkp_status\", \"nama_bank\", \"no_rekening\", \"mata_uang\", \"penanggung_jawab\", \"jabatan_pj\", \"nama_sales\", \"no_telp\", \"alamat_email\", \"media_sosial\") VALUES (:no_vendor, :nama_vendor, :badan_usaha, :alamat_vendor, :nib, :npwp, :pkp_status, :nama_bank, :no_rekening, :mata_uang, :penanggung_jawab, :jabatan_pj, :nama_sales, :no_telp, :alamat_email, :media_sosial) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_vendor'] = $input['no_vendor'] ?? null;
        $params[':nama_vendor'] = $input['nama_vendor'] ?? null;
        $params[':badan_usaha'] = $input['badan_usaha'] ?? null;
        $params[':alamat_vendor'] = $input['alamat_vendor'] ?? null;
        $params[':nib'] = $input['nib'] ?? null;
        $params[':npwp'] = $input['npwp'] ?? null;
        $params[':pkp_status'] = $input['pkp_status'] ?? null;
        $params[':nama_bank'] = $input['nama_bank'] ?? null;
        $params[':no_rekening'] = $input['no_rekening'] ?? null;
        $params[':mata_uang'] = $input['mata_uang'] ?? null;
        $params[':penanggung_jawab'] = $input['penanggung_jawab'] ?? null;
        $params[':jabatan_pj'] = $input['jabatan_pj'] ?? null;
        $params[':nama_sales'] = $input['nama_sales'] ?? null;
        $params[':no_telp'] = $input['no_telp'] ?? null;
        $params[':alamat_email'] = $input['alamat_email'] ?? null;
        $params[':media_sosial'] = $input['media_sosial'] ?? null;
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
        $sql = "UPDATE \"vendor\" SET "\"no_vendor\" = :no_vendor", "\"nama_vendor\" = :nama_vendor", "\"badan_usaha\" = :badan_usaha", "\"alamat_vendor\" = :alamat_vendor", "\"nib\" = :nib", "\"npwp\" = :npwp", "\"pkp_status\" = :pkp_status", "\"nama_bank\" = :nama_bank", "\"no_rekening\" = :no_rekening", "\"mata_uang\" = :mata_uang", "\"penanggung_jawab\" = :penanggung_jawab", "\"jabatan_pj\" = :jabatan_pj", "\"nama_sales\" = :nama_sales", "\"no_telp\" = :no_telp", "\"alamat_email\" = :alamat_email", "\"media_sosial\" = :media_sosial" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_vendor'] = array_key_exists('no_vendor', $input) ? $input['no_vendor'] : null;
        $params[':nama_vendor'] = array_key_exists('nama_vendor', $input) ? $input['nama_vendor'] : null;
        $params[':badan_usaha'] = array_key_exists('badan_usaha', $input) ? $input['badan_usaha'] : null;
        $params[':alamat_vendor'] = array_key_exists('alamat_vendor', $input) ? $input['alamat_vendor'] : null;
        $params[':nib'] = array_key_exists('nib', $input) ? $input['nib'] : null;
        $params[':npwp'] = array_key_exists('npwp', $input) ? $input['npwp'] : null;
        $params[':pkp_status'] = array_key_exists('pkp_status', $input) ? $input['pkp_status'] : null;
        $params[':nama_bank'] = array_key_exists('nama_bank', $input) ? $input['nama_bank'] : null;
        $params[':no_rekening'] = array_key_exists('no_rekening', $input) ? $input['no_rekening'] : null;
        $params[':mata_uang'] = array_key_exists('mata_uang', $input) ? $input['mata_uang'] : null;
        $params[':penanggung_jawab'] = array_key_exists('penanggung_jawab', $input) ? $input['penanggung_jawab'] : null;
        $params[':jabatan_pj'] = array_key_exists('jabatan_pj', $input) ? $input['jabatan_pj'] : null;
        $params[':nama_sales'] = array_key_exists('nama_sales', $input) ? $input['nama_sales'] : null;
        $params[':no_telp'] = array_key_exists('no_telp', $input) ? $input['no_telp'] : null;
        $params[':alamat_email'] = array_key_exists('alamat_email', $input) ? $input['alamat_email'] : null;
        $params[':media_sosial'] = array_key_exists('media_sosial', $input) ? $input['media_sosial'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"vendor\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
