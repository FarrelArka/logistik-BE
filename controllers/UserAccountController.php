<?php
require_once __DIR__ . '/../config/database.php';

class UserAccountController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"user_account\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"user_account\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"user_account\" (\"username\", \"password_hash\", \"nama_lengkap\", \"peran_id\", \"departemen_id\", \"divisi_id\", \"mfa_status\", \"mfa_secret\", \"last_login\") VALUES (:username, :password_hash, :nama_lengkap, :peran_id, :departemen_id, :divisi_id, :mfa_status, :mfa_secret, :last_login) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':username'] = $input['username'] ?? null;
        $params[':password_hash'] = $input['password_hash'] ?? null;
        $params[':nama_lengkap'] = $input['nama_lengkap'] ?? null;
        $params[':peran_id'] = $input['peran_id'] ?? null;
        $params[':departemen_id'] = $input['departemen_id'] ?? null;
        $params[':divisi_id'] = $input['divisi_id'] ?? null;
        $params[':mfa_status'] = $input['mfa_status'] ?? null;
        $params[':mfa_secret'] = $input['mfa_secret'] ?? null;
        $params[':last_login'] = $input['last_login'] ?? null;
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
        $sql = "UPDATE \"user_account\" SET "\"username\" = :username", "\"password_hash\" = :password_hash", "\"nama_lengkap\" = :nama_lengkap", "\"peran_id\" = :peran_id", "\"departemen_id\" = :departemen_id", "\"divisi_id\" = :divisi_id", "\"mfa_status\" = :mfa_status", "\"mfa_secret\" = :mfa_secret", "\"last_login\" = :last_login" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':username'] = array_key_exists('username', $input) ? $input['username'] : null;
        $params[':password_hash'] = array_key_exists('password_hash', $input) ? $input['password_hash'] : null;
        $params[':nama_lengkap'] = array_key_exists('nama_lengkap', $input) ? $input['nama_lengkap'] : null;
        $params[':peran_id'] = array_key_exists('peran_id', $input) ? $input['peran_id'] : null;
        $params[':departemen_id'] = array_key_exists('departemen_id', $input) ? $input['departemen_id'] : null;
        $params[':divisi_id'] = array_key_exists('divisi_id', $input) ? $input['divisi_id'] : null;
        $params[':mfa_status'] = array_key_exists('mfa_status', $input) ? $input['mfa_status'] : null;
        $params[':mfa_secret'] = array_key_exists('mfa_secret', $input) ? $input['mfa_secret'] : null;
        $params[':last_login'] = array_key_exists('last_login', $input) ? $input['last_login'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"user_account\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
