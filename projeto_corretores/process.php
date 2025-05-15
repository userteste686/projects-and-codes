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
    $cpf = $_POST["cpf"];
    $creci = $_POST["creci"];
    $nome = $_POST["nome"];

    echo "Recebendo: CPF = $cpf, CRECI = $creci, Nome = $nome <br>";

    $sql = "INSERT INTO corretores (cpf, creci, nome) VALUES ('$cpf', '$creci', '$nome')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.html");
    } else {
        echo "Erro: " . $conn->error;
    }
}

$conn->close();
?>
