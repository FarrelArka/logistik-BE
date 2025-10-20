<?php
// tools/generate_crud.php
// Usage: php tools/generate_crud.php
require __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '5432';
$db   = $_ENV['DB_NAME'] ?? 'logistik';
$user = $_ENV['DB_USER'] ?? 'postgres';
$pass = $_ENV['DB_PASS'] ?? '';

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    die("DB connect failed: " . $e->getMessage() . PHP_EOL);
}

$outControllers = __DIR__ . '/../controllers';
$outRoutes      = __DIR__ . '/../routes';
if (!is_dir($outControllers)) mkdir($outControllers, 0755, true);
if (!is_dir($outRoutes)) mkdir($outRoutes, 0755, true);

// get tables in public schema
$tablesStmt = $pdo->prepare("
    SELECT table_name
    FROM information_schema.tables
    WHERE table_schema = 'public' AND table_type='BASE TABLE'
    ORDER BY table_name;
");
$tablesStmt->execute();
$tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

function toPascal($s){
    $s = str_replace(['-','_'], ' ', $s);
    $s = ucwords($s);
    return str_replace(' ', '', $s);
}

function toCamel($s){
    $p = toPascal($s);
    return lcfirst($p);
}

foreach($tables as $table){
    // skip migrations or vendor tables if any
    if (in_array($table, ['migrations'])) continue;

    // columns
    $colStmt = $pdo->prepare("SELECT column_name, data_type, is_nullable FROM information_schema.columns WHERE table_schema='public' AND table_name = :t ORDER BY ordinal_position");
    $colStmt->execute([':t'=>$table]);
    $cols = $colStmt->fetchAll(PDO::FETCH_ASSOC);

    // primary key guess: id
    $pk = 'id';
    foreach($cols as $c) if ($c['column_name'] === 'id') { $pk = 'id'; break; }

    // detect foreign keys
    $fkStmt = $pdo->prepare("
        SELECT
          kcu.column_name,
          ccu.table_name AS foreign_table_name,
          ccu.column_name AS foreign_column_name
        FROM information_schema.table_constraints AS tc
        JOIN information_schema.key_column_usage AS kcu
          ON tc.constraint_name = kcu.constraint_name AND tc.table_schema = kcu.table_schema
        JOIN information_schema.constraint_column_usage AS ccu
          ON ccu.constraint_name = tc.constraint_name AND ccu.table_schema = tc.table_schema
        WHERE tc.constraint_type = 'FOREIGN KEY' AND tc.table_name = :t AND tc.table_schema = 'public';
    ");
    $fkStmt->execute([':t'=>$table]);
    $fks = $fkStmt->fetchAll(PDO::FETCH_ASSOC);

    $Pascal = toPascal($table);
    $camel  = toCamel($table);
    $controllerFile = $outControllers . "/{$Pascal}Controller.php";
    $routeFile = $outRoutes . "/{$table}.php";

    // build controller content
    $controller = "<?php\n";
    $controller .= "require_once __DIR__ . '/../config/database.php';\n\n";
    $controller .= "class {$Pascal}Controller {\n";
    $controller .= "    private \$db;\n    public function __construct(){\n";
    $controller .= "        \$database = new Database();\n        \$this->db = \$database->connect();\n    }\n\n";
    // index
    $controller .= "    public function index(){\n";
    $controller .= "        \$stmt = \$this->db->prepare(\"SELECT * FROM \\\"{$table}\\\"\");\n";
    $controller .= "        \$stmt->execute();\n";
    $controller .= "        \$data = \$stmt->fetchAll(PDO::FETCH_ASSOC);\n";
    $controller .= "        header('Content-Type: application/json');\n";
    $controller .= "        echo json_encode(\$data);\n    }\n\n";
    // show
    $controller .= "    public function show(\$id){\n";
    $controller .= "        \$stmt = \$this->db->prepare(\"SELECT * FROM \\\"{$table}\\\" WHERE {$pk} = :id LIMIT 1\");\n";
    $controller .= "        \$stmt->execute([':id'=>\$id]);\n";
    $controller .= "        \$data = \$stmt->fetch(PDO::FETCH_ASSOC);\n";
    $controller .= "        header('Content-Type: application/json');\n";
    $controller .= "        echo json_encode(\$data);\n    }\n\n";
    // store
    $controller .= "    public function store(){\n";
    $controller .= "        \$input = json_decode(file_get_contents('php://input'), true);\n";
    // build insert columns excluding id and timestamps defaulted
    $insertCols = array_filter(array_map(function($c){ return $c['column_name']; }, $cols), function($col){ return $col !== 'id'; });
    $colList = implode(', ', array_map(function($c){ return '\"'.$c.'\"'; }, $insertCols));
    $paramList = implode(', ', array_map(function($c){ return ':'.$c; }, $insertCols));
    $controller .= "        \$sql = \"INSERT INTO \\\"{$table}\\\" ({$colList}) VALUES ({$paramList}) RETURNING {$pk}\";\n";
    $controller .= "        \$stmt = \$this->db->prepare(\$sql);\n";
    $controller .= "        \$params = [];\n";
    foreach($insertCols as $c){
        $controller .= "        \$params[':{$c}'] = \$input['{$c}'] ?? null;\n";
    }
    $controller .= "        try{\n            \$stmt->execute(\$params);\n            \$id = \$stmt->fetchColumn();\n            echo json_encode(['status'=>'ok','id'=>\$id]);\n        }catch(PDOException \$e){\n            http_response_code(400);\n            echo json_encode(['error'=>\$e->getMessage()]);\n        }\n    }\n\n";
    // update
    $controller .= "    public function update(\$id){\n";
    $controller .= "        \$input = json_decode(file_get_contents('php://input'), true);\n";
    $setParts = [];
    foreach($insertCols as $c){
        $setParts[] = "\"\\\"$c\\\" = :$c\"";
    }
    $setSql = implode(', ', $setParts);
    $controller .= "        \$sql = \"UPDATE \\\"{$table}\\\" SET {$setSql} WHERE {$pk} = :id\";\n";
    $controller .= "        \$stmt = \$this->db->prepare(\$sql);\n";
    $controller .= "        \$params = [];\n";
    foreach($insertCols as $c){
        $controller .= "        \$params[':{$c}'] = array_key_exists('{$c}', \$input) ? \$input['{$c}'] : null;\n";
    }
    $controller .= "        \$params[':id'] = \$id;\n";
    $controller .= "        try{\n            \$stmt->execute(\$params);\n            echo json_encode(['status'=>'ok']);\n        }catch(PDOException \$e){\n            http_response_code(400);\n            echo json_encode(['error'=>\$e->getMessage()]);\n        }\n    }\n\n";
    // delete
    $controller .= "    public function delete(\$id){\n";
    $controller .= "        \$stmt = \$this->db->prepare(\"DELETE FROM \\\"{$table}\\\" WHERE {$pk} = :id\");\n";
    $controller .= "        try{\n            \$stmt->execute([':id'=>\$id]);\n            echo json_encode(['status'=>'deleted']);\n        }catch(PDOException \$e){\n            http_response_code(400);\n            echo json_encode(['error'=>\$e->getMessage()]);\n        }\n    }\n";
    $controller .= "}\n";

    // write controller file
    file_put_contents($controllerFile, $controller);
    echo "Generated controller: {$controllerFile}\n";

    // build route file
    $route = "<?php\nuse Bramus\\Router\\Router;\nrequire_once __DIR__ . '/../controllers/{$Pascal}Controller.php';\n\n\$router = new Router();\n\$ctrl = new {$Pascal}Controller();\n\n\$router->get('/{$table}', fn() => \$ctrl->index());\n\$router->get('/{$table}/(\\d+)', fn(\$id) => \$ctrl->show(\$id));\n\$router->post('/{$table}', fn() => \$ctrl->store());\n\$router->put('/{$table}/(\\d+)', fn(\$id) => \$ctrl->update(\$id));\n\$router->delete('/{$table}/(\\d+)', fn(\$id) => \$ctrl->delete(\$id));\n\nreturn \$router;\n";
    file_put_contents($routeFile, $route);
    echo "Generated route: {$routeFile}\n";
}

// generate all_routes.php to include all route files
$allRoutes = "<?php\nuse Bramus\\Router\\Router;\n\$router = new Router();\n";
foreach($tables as $table){
    if ($table === 'migrations') continue;
    $allRoutes .= "require __DIR__ . '/{$table}.php';\n";
}
// Note: individual route files return a Router object; to keep simple, we instead require them in index.php below.
file_put_contents(__DIR__ . '/../routes/all_routes.php', $allRoutes);
echo "Done. Controllers and routes generated in controllers/ and routes/.\n";
