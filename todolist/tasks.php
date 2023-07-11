<?php
// Configurações do banco de dados
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'todolistdb';

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
}

// Verifica o método da requisição
$method = $_SERVER['REQUEST_METHOD'];

// Obtém as tarefas existentes
if ($method === 'GET') {
  $tasks = array();

  $sql = 'SELECT id, name FROM tasks';
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $task = array(
        'id' => $row['id'],
        'name' => $row['name']
      );

      array_push($tasks, $task);
    }
  }

  echo json_encode($tasks);
}

// Adiciona uma nova tarefa
if ($method === 'POST') {
  $name = $_POST['name'];

  $sql = 'INSERT INTO tasks (name) VALUES (?)';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $name);

  if ($stmt->execute() === TRUE) {
    echo 'Tarefa adicionada com sucesso.';
  } else {
    echo 'Erro ao adicionar a tarefa: ' . $conn->error;
  }
}

// Exclui uma tarefa existente
if ($method === 'DELETE') {
  parse_str(file_get_contents('php://input'), $deleteParams);
  $id = $deleteParams['id'];

  $sql = 'DELETE FROM tasks WHERE id = ?';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $id);

  if ($stmt->execute() === TRUE) {
    echo 'Tarefa excluída com sucesso.';
  } else {
    echo 'Erro ao excluir a tarefa: ' . $conn->error;
  }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
