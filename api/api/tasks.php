<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';

$database = new Database();
$db = $database->connect();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

switch ($method) {    
    case 'GET':
        if ($id) { 
            $stmt = $db->prepare("SELECT * FROM tasks WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($task) {
                echo json_encode($task);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Task not found.']);
            }
        } else {
            $stmt = $db->query("SELECT * FROM tasks ORDER BY created_at DESC");
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($tasks);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
       
        if (empty($data->title)) {
            http_response_code(400); 
            echo json_encode(['message' => 'Title is required.']);
            exit();
        }

        $query = "INSERT INTO tasks (title, description, status) VALUES (:title, :description, :status)";
        $stmt = $db->prepare($query);
        
        $title = htmlspecialchars(strip_tags($data->title));
          $description = isset($data->description) ? htmlspecialchars(strip_tags($data->description)) : '';
         $status = isset($data->status) ? htmlspecialchars(strip_tags($data->status)) : 'pending';

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Task created.', 'id' => $db->lastInsertId()]);
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(['message' => 'Unable to create task.']);
        }
        break;

       case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

       // проверка - валидация
        if (!$id || empty($data->title) || empty($data->status)) {
            http_response_code(400);
            echo json_encode(['message' => 'ID, title and status are required.']);
            exit();
        }

        $query = "UPDATE tasks SET title = :title, description = :description, status = :status WHERE id = :id";
        $stmt = $db->prepare($query);

        $title = htmlspecialchars(strip_tags($data->title));
         $description = isset($data->description) ? htmlspecialchars(strip_tags($data->description)) : '';
        $status = htmlspecialchars(strip_tags($data->status));

        $stmt->bindParam(':id', $id);
         $stmt->bindParam(':title', $title);
         $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                 echo json_encode(['message' => 'Task updated.']);
            } else {
                 http_response_code(404);
                 echo json_encode(['message' => 'Task not found or no changes made.']);
            }
        } else {
            http_response_code(503);
            echo json_encode(['message' => 'Unable to update task.']);
        }
        break;

        case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'ID is required.']);
            exit();
        }

        $query = "DELETE FROM tasks WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(['message' => 'Task deleted.']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Task not found.']);
            }
        } else {
            http_response_code(503);
            echo json_encode(['message' => 'Unable to delete task.']);
        }
        break;

    default:
        http_response_code(405); 
        echo json_encode(['message' => 'Method not allowed.']);
        break;
}
?>
