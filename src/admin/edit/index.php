<?php
    $dsn = 'mysql:host=db;dbname=posse;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user ,$password);
    $todos = $dbh->query("SELECT * FROM todos")->fetchAll();

    // if (!isset($_SESSION['id'])) {
    //     header('Location: ./auth/login.php');
    //     exit;
    //   }

      if (!$_GET['id']) {
        header('Location: ../index.php');
        exit;
      }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $text = trim($_POST['content']);
        if ($text) {
        $stmt = $dbh->prepare("UPDATE todos SET content = :content WHERE id = :id");
        $stmt->bindValue(':content', $text);
        $stmt->bindValue(':id', $_GET['id']);
        $stmt->execute();
        header('Location: ../index.php');
        exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
    <script src="/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="/node_modules/sweetalert2/dist/sweetalert2.min.css">
</head>
<body>
<?php include __DIR__ . '/../components/header.php'; ?>
        <div class="p-10">
            <div class="w-full flex-wrap  flex justify-center items-center flex-col">
                <form method="POST">
                    <form method="GET" action="edit/index.php">
                        <label for="content">
                            <input type="text" name="content" placeholder="新しいtodoを入力してください" class="border border-slate-600 p-2 mb-5 max-w-lg w-full" id="todo-input" value="<?=htmlspecialchars($_GET['content'])?>">
                        </label>
                        <button  class="mb-5 py-2 px-4 bg-blue-700 hover:bg-blue-900 text-white font-bold justify-center rounded w-40 space-y-4 " id="todo-button">追加</button>
                    </form>
                </form>
            </div>
        </div>
</body>
</html>