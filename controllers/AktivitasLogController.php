<?php
require_once __DIR__ . '/../config/database.php';

class AktivitasLogController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"aktivitas_log\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"aktivitas_log\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"aktivitas_log\" (\"user_id\", \"waktu_aktivitas\", \"modul_terkait\", \"deskripsi_aktivitas\", \"hak_akses_terakhir\", \"perubahan_peran_dari\", \"perubahan_peran_ke\") VALUES (:user_id, :waktu_aktivitas, :modul_terkait, :deskripsi_aktivitas, :hak_akses_terakhir, :perubahan_peran_dari, :perubahan_peran_ke) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':user_id'] = $input['user_id'] ?? null;
        $params[':waktu_aktivitas'] = $input['waktu_aktivitas'] ?? null;
        $params[':modul_terkait'] = $input['modul_terkait'] ?? null;
        $params[':deskripsi_aktivitas'] = $input['deskripsi_aktivitas'] ?? null;
        $params[':hak_akses_terakhir'] = $input['hak_akses_terakhir'] ?? null;
        $params[':perubahan_peran_dari'] = $input['perubahan_peran_dari'] ?? null;
        $params[':perubahan_peran_ke'] = $input['perubahan_peran_ke'] ?? null;
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
        $sql = "UPDATE \"aktivitas_log\" SET "\"user_id\" = :user_id", "\"waktu_aktivitas\" = :waktu_aktivitas", "\"modul_terkait\" = :modul_terkait", "\"deskripsi_aktivitas\" = :deskripsi_aktivitas", "\"hak_akses_terakhir\" = :hak_akses_terakhir", "\"perubahan_peran_dari\" = :perubahan_peran_dari", "\"perubahan_peran_ke\" = :perubahan_peran_ke" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':user_id'] = array_key_exists('user_id', $input) ? $input['user_id'] : null;
        $params[':waktu_aktivitas'] = array_key_exists('waktu_aktivitas', $input) ? $input['waktu_aktivitas'] : null;
        $params[':modul_terkait'] = array_key_exists('modul_terkait', $input) ? $input['modul_terkait'] : null;
        $params[':deskripsi_aktivitas'] = array_key_exists('deskripsi_aktivitas', $input) ? $input['deskripsi_aktivitas'] : null;
        $params[':hak_akses_terakhir'] = array_key_exists('hak_akses_terakhir', $input) ? $input['hak_akses_terakhir'] : null;
        $params[':perubahan_peran_dari'] = array_key_exists('perubahan_peran_dari', $input) ? $input['perubahan_peran_dari'] : null;
        $params[':perubahan_peran_ke'] = array_key_exists('perubahan_peran_ke', $input) ? $input['perubahan_peran_ke'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"aktivitas_log\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
