<?php
require_once __DIR__ . '/../config/database.php';

class HakAksesController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"hak_akses\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"hak_akses\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"hak_akses\" (\"peran_id\", \"modul_id\", \"can_create\", \"can_read\", \"can_update\", \"can_execute\", \"can_delete\") VALUES (:peran_id, :modul_id, :can_create, :can_read, :can_update, :can_execute, :can_delete) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':peran_id'] = $input['peran_id'] ?? null;
        $params[':modul_id'] = $input['modul_id'] ?? null;
        $params[':can_create'] = $input['can_create'] ?? null;
        $params[':can_read'] = $input['can_read'] ?? null;
        $params[':can_update'] = $input['can_update'] ?? null;
        $params[':can_execute'] = $input['can_execute'] ?? null;
        $params[':can_delete'] = $input['can_delete'] ?? null;
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
        $sql = "UPDATE \"hak_akses\" SET "\"peran_id\" = :peran_id", "\"modul_id\" = :modul_id", "\"can_create\" = :can_create", "\"can_read\" = :can_read", "\"can_update\" = :can_update", "\"can_execute\" = :can_execute", "\"can_delete\" = :can_delete" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':peran_id'] = array_key_exists('peran_id', $input) ? $input['peran_id'] : null;
        $params[':modul_id'] = array_key_exists('modul_id', $input) ? $input['modul_id'] : null;
        $params[':can_create'] = array_key_exists('can_create', $input) ? $input['can_create'] : null;
        $params[':can_read'] = array_key_exists('can_read', $input) ? $input['can_read'] : null;
        $params[':can_update'] = array_key_exists('can_update', $input) ? $input['can_update'] : null;
        $params[':can_execute'] = array_key_exists('can_execute', $input) ? $input['can_execute'] : null;
        $params[':can_delete'] = array_key_exists('can_delete', $input) ? $input['can_delete'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"hak_akses\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
