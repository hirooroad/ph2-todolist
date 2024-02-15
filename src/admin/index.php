<?php
require __DIR__ . '/dbconnect.php';

session_start();

if(!isset($_SESSION['id'])) {
    header('Location: ./auth/login.php');
    exit;
}

$userId = $_SESSION['id'];
    $dsn = 'mysql:host=db;dbname=posse;charset=utf8';
    $user = 'root';
    $password = 'root';
    $todos = $dbh->prepare("SELECT * FROM todos WHERE user_id = :user_id");
    $todos->bindValue(':user_id', $userId);
    $todos->execute();
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
<?php include __DIR__ . '/components/header.php'; ?>
        <div class="p-10">
            <div class="w-full flex-wrap  flex justify-center items-center flex-col">
                <form method="POST">
                        <label for="content">
                            <input id="todo-text" type="text" name="content" placeholder="新しいtodoを入力してください" class="border border-slate-600 p-2 mb-5 max-w-lg w-full" id="todo-input">
                        </label>
                        <!-- <button type="button" onclick='createTodo()' class="mb-5 py-2 px-4 bg-blue-700 hover:bg-blue-900 text-white font-bold justify-center rounded w-40 space-y-4 " id="todo-button">追加</button> -->
                        <button type="button" class="mb-5 py-2 px-4 bg-blue-700 hover:bg-blue-900 text-white font-bold justify-center rounded w-40 space-y-4 " id="todo-button">追加</button>
                </form>
                <ul id="todo-ul" class="list-none">
                <?php foreach($todos as $todo) { ?>
                    <li><div class='text-center my-4'> <?=$todo['content']?>
                        <button type="button" class='mx-4 px-1 py-2 bg-blue-500 hover:bg-blue-700 font-bold   rounded w-40 text-white'>complete</button>
                        <a class='font-bold rounded w-40 mx-2 px-1 py-2 bg-orange-500 hover:bg-orange-700 text-white' href='edit/index.php?id=<?= $todo['id'] ?>&content=<?= $todo['content']?>'>edit</a>
                        <button class='font-bold rounded w-40 mx-2 px-1 py-2 bg-red-500 hover:bg-red-700 text-white'>delete</button>
                </div>
                </li>
                <?}?>
                </ul>
            </div>
        </div>
        <template>
    <li class="flex items-center justify-center">
    <span class="js-todo-text"></span>
    <button type="button" class="ml-2 px-3 py-1 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded js-complete-todo-template" data-id="">
        Complete
      </button>
      <a href="" class="ml-2 px-3 py-1 bg-yellow-500 hover:bg-yellow-700 text-white font-bold rounded js-edit-link">Edit</a>
      <button type="button" class="ml-2 px-3 py-1 bg-red-500 hover:bg-red-600 text-white font-bold rounded js-delete-todo-template" data-id="">
        Delete
      </button>
    </li>
  </template>
</body>

<script>
    const addTodoElement = (text,id) => {
        const template = document.querySelector('template').content.cloneNode(true);
        template.querySelector('.js-todo-text').textContent = text;

        const completeButton = template.querySelector('.js-complete-todo-template');
        completeButton.setAttribute(('data-id' , id));
        completeButton.addEventListener('click', () => {
            updateTodo(id);
        });

        template.querySelector('.js-edit-link').href = `edit/index.php?id=${id}&text=${text}`;

        const deleteButton = template.querySelector('.js-delete-todo-template');
        deleteButton.setAttribute('data-id', id);
        deleteButton.addEventListener('click', () => {
        deleteTodo(id, deleteButton.parentNode);
    });

    document.querySelector('.js-todo-list').appendChild(template);
    }

    document.getElementById('todo-button').addEventListener('click', createTodo);

    async function createTodo() {
        const todoInput = document.getElementById('todo-text');
        const todoText = todoInput.value;
        console.log(todoText);
    //     const formData = new URLSearchParams();
    // formData.append('todo-text', todoText);
        try {
            console.log('1');
            const response = await fetch('./create/index.php' , {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json',
            },
            body:   JSON.stringify({ "content" : todoText }),
            });
            console.log('2');
            if (!response.ok) {
            const errorText = await response.text();
            throw new Error('Error from server: ' + errorText);
            }

        const data = await response.json();
        console.log(data);
        addTodoElement(todoText, data.id);
        console.log('3');
        todoInput.value = '' ;
            } catch(error) {
                alert('Error: ' + error.message);
            }
        }
</script>
</html>