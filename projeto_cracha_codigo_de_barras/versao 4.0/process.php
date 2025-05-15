<?php

/*$ip_usuario = $_SERVER['REMOTE_ADDR'];
$ip_empresa = '172.17.90.0/24'; // troque para a faixa da sua rede

function ipNaFaixa($ip, $cidr) {
    list($base, $bits) = explode('/', $cidr);
    $ip_dec = ip2long($ip);
    $base_dec = ip2long($base);
    $mask = -1 << (32 - $bits);
    return ($ip_dec & $mask) === ($base_dec & $mask);
}

if (!ipNaFaixa($ip_usuario, $ip_empresa)) {
    die("Acesso negado: fora da rede corporativa.");
}*/

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

