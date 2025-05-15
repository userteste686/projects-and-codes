<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "corretorDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Se um ID foi passado na URL, busca os dados do corretor
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Converte para inteiro (evita SQL Injection)
    $sql = "SELECT * FROM corretores WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $corretor = $result->fetch_assoc();
    } else {
        echo "Corretor não encontrado!";
        exit();
    }
}

// Se o formulário for enviado, atualiza os dados no banco
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

    // Atualiza no banco
    $sql = "UPDATE corretores SET cpf = ?, creci = ?, nome = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $cpf, $creci, $nome, $id);

    if ($stmt->execute()) {
        echo "Sucesso!!! Corretor editado com sucesso!!!";
        exit();
    } else {
        echo "Erro!!! Não foi possivel editar o corretor!!!";
    }
}

$conn->close();
?>

