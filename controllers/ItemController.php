<?php
require_once __DIR__ . '/../config/database.php';

class ItemController {
    private $db;
    public function __construct(){
        $database = new Database();
        $this->db = $database->connect();
    }

    public function index(){
        $stmt = $this->db->prepare("SELECT * FROM \"item\"");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function show($id){
        $stmt = $this->db->prepare("SELECT * FROM \"item\" WHERE id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function store(){
        $input = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO \"item\" (\"no_id_item\", \"nama_item\", \"barcode_produk\", \"barcode_item\", \"satuan_terkecil\", \"satuan_terbesar\", \"konversi_qty\", \"par_stock_terkecil\", \"group_id\", \"class_id\", \"kategori\") VALUES (:no_id_item, :nama_item, :barcode_produk, :barcode_item, :satuan_terkecil, :satuan_terbesar, :konversi_qty, :par_stock_terkecil, :group_id, :class_id, :kategori) RETURNING id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_id_item'] = $input['no_id_item'] ?? null;
        $params[':nama_item'] = $input['nama_item'] ?? null;
        $params[':barcode_produk'] = $input['barcode_produk'] ?? null;
        $params[':barcode_item'] = $input['barcode_item'] ?? null;
        $params[':satuan_terkecil'] = $input['satuan_terkecil'] ?? null;
        $params[':satuan_terbesar'] = $input['satuan_terbesar'] ?? null;
        $params[':konversi_qty'] = $input['konversi_qty'] ?? null;
        $params[':par_stock_terkecil'] = $input['par_stock_terkecil'] ?? null;
        $params[':group_id'] = $input['group_id'] ?? null;
        $params[':class_id'] = $input['class_id'] ?? null;
        $params[':kategori'] = $input['kategori'] ?? null;
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
        $sql = "UPDATE \"item\" SET "\"no_id_item\" = :no_id_item", "\"nama_item\" = :nama_item", "\"barcode_produk\" = :barcode_produk", "\"barcode_item\" = :barcode_item", "\"satuan_terkecil\" = :satuan_terkecil", "\"satuan_terbesar\" = :satuan_terbesar", "\"konversi_qty\" = :konversi_qty", "\"par_stock_terkecil\" = :par_stock_terkecil", "\"group_id\" = :group_id", "\"class_id\" = :class_id", "\"kategori\" = :kategori" WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $params = [];
        $params[':no_id_item'] = array_key_exists('no_id_item', $input) ? $input['no_id_item'] : null;
        $params[':nama_item'] = array_key_exists('nama_item', $input) ? $input['nama_item'] : null;
        $params[':barcode_produk'] = array_key_exists('barcode_produk', $input) ? $input['barcode_produk'] : null;
        $params[':barcode_item'] = array_key_exists('barcode_item', $input) ? $input['barcode_item'] : null;
        $params[':satuan_terkecil'] = array_key_exists('satuan_terkecil', $input) ? $input['satuan_terkecil'] : null;
        $params[':satuan_terbesar'] = array_key_exists('satuan_terbesar', $input) ? $input['satuan_terbesar'] : null;
        $params[':konversi_qty'] = array_key_exists('konversi_qty', $input) ? $input['konversi_qty'] : null;
        $params[':par_stock_terkecil'] = array_key_exists('par_stock_terkecil', $input) ? $input['par_stock_terkecil'] : null;
        $params[':group_id'] = array_key_exists('group_id', $input) ? $input['group_id'] : null;
        $params[':class_id'] = array_key_exists('class_id', $input) ? $input['class_id'] : null;
        $params[':kategori'] = array_key_exists('kategori', $input) ? $input['kategori'] : null;
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
        $stmt = $this->db->prepare("DELETE FROM \"item\" WHERE id = :id");
        try{
            $stmt->execute([':id'=>$id]);
            echo json_encode(['status'=>'deleted']);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }
}
