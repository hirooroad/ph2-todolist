<?
require __DIR__ . '/../dbconnect.php';

session_start();

$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

var_dump($data);
if (!isset($_SESSION['id'])) {
    header('Location: /auth/login.php');
    exit;
}
var_dump($data);
if (!isset($data['content'])) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Bad Request: todo-text is missing';
    exit;
}

try {
    $stmt = $dbh->prepare("INSERT INTO todos(user_id, content) VALUES(:user_id, :content)");
    $stmt->bindValue(':user_id' , $_SESSION['id']);
    $stmt->bindValue(':content' , $_POST["content"]);
    $stmt->execute();

    $newlyInsertedId = $dbh->lastInsertId();
    echo json_encode(['id' => $newlyInsertedId]);
} catch (PDOException $e) {
    header('HTTP/1.1 500 Internal Server Error');
    exit;
}