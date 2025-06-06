<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "corretorDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $cpf = trim($_POST['cpf']);
    $creci = trim($_POST['creci']);
    $nome = trim($_POST['nome']);

    // Validação básica
    if (strlen($cpf) != 11 || strlen($creci) < 2 || strlen($nome) < 2) {
        echo "Erro: CPF deve ter 11 caracteres, Creci e Nome devem ter pelo menos 2 caracteres.";
        exit();
    }

    // Atualiza os dados no banco
    $sql = "UPDATE corretores SET cpf = ?, creci = ?, nome = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $cpf, $creci, $nome, $id);

    if ($stmt->execute()) {
        // Redireciona para index.html com mensagem de sucesso
        header("Location: index.html?msg=Corretor atualizado com sucesso!");
        exit();
    } else {
        echo "Erro ao atualizar o corretor: " . $stmt->error;
    }
}

$conn->close();
?>
