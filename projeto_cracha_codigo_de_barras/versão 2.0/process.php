<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "empresaDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cracha = $conn->real_escape_string($_POST["cracha"]);
    $tipo = $conn->real_escape_string($_POST["tipo"]);

    // Verifica se o crachá contém apenas números
    if (!preg_match('/^\d+$/', $cracha)) {
            die("Crachá invalido!!!");
    }

    $sql = "INSERT INTO registros (cracha, tipo, horario) VALUES ('$cracha', '$tipo', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: func.php");
    } else {
        echo "Erro: " . $conn->error;
    }
}

$conn->close();
?>

