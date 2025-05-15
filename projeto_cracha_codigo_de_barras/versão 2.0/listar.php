<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "empresaDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$sql = "SELECT * FROM registros ORDER BY horario DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['cracha']}</td>
                <td>{$row['tipo']}</td>
                <td>{$row['horario']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>Nenhum registro encontrado.</td></tr>";
}

$conn->close();
?>
