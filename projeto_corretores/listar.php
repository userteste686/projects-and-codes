<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "corretorDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$sql = "SELECT * FROM corretores";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['cpf']}</td>
                <td>{$row['creci']}</td>
                <td>{$row['nome']}</td>
                <td>
                    <a href='editar.html?id=" . $row['id'] . "&cpf=" . urlencode($row['cpf']) . "&creci=" . urlencode($row['creci']) . "&nome=" . urlencode($row['nome']) . "' class='btn-editar'>Editar</a>
                    <a href='excluir.php?id={$row['id']}'>Excluir</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>Nenhum corretor cadastrado.</td></tr>";
}

$conn->close();
?>
