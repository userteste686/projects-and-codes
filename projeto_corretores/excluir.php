<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "corretorDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "DELETE FROM corretores WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.html");
    } else {
        echo "Erro ao excluir: " . $conn->error;
    }
}

$conn->close();
?>
