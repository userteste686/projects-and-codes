<?php
session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: login_admin.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "empresaDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if (isset($_GET['exportar']) && $_GET['exportar'] == 'csv') {
    $dataFiltro = isset($_GET['data']) ? $_GET['data'] : '';
    
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename=registros_ponto_' . date('Y-m-d') . '.xls');
    
    $output = fopen('php://output', 'w');
    
    // Cabeçalhos do CSV
    fputcsv($output, array('ID', 'Cracha', 'Tipo', 'Data e Hora'), "\t");
    
    // Query para obter os dados
    $sql = "SELECT id, cracha, tipo, horario FROM registros";
    if (!empty($dataFiltro)) {
        $sql .= " WHERE DATE(horario) = ? ORDER BY horario DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $dataFiltro);
    } else {
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row, "\t");
    }
    
    fclose($output);
    exit();
}

$dataFiltro = isset($_GET['data']) ? $_GET['data'] : '';

$sql = "SELECT * FROM registros";

if (!empty($dataFiltro)) {
    $sql .= " WHERE DATE(horario) = ? ORDER BY horario DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dataFiltro);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;

// Conta total de registros (com ou sem filtro de data)
$countSql = "SELECT COUNT(*) AS total FROM registros";
if (!empty($dataFiltro)) {
    $countSql .= " WHERE DATE(horario) = ?";
    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("s", $dataFiltro);
} else {
    $countStmt = $conn->prepare($countSql);
}
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRegistros = $countResult->fetch_assoc()['total'];
$totalPaginas = ceil($totalRegistros / $limite);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Ponto Digital - Administração</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="style.css">
    <script defer src="admin.js"></script>
</head>

<body>
<div class="app-container">
        <!-- Cabeçalho -->
        <header class="app-header">
            <div class="logo">
                <i class="fas fa-fingerprint"></i>
                <h1>Painel Administrativo</h1>
            </div>
            <div class="admin-link">
                <h2><a href="logout.php"><i class="fas fa-user-shield"></i> Sair</a></h2>
            </div>
            <div class="clock-container">
                <i class="far fa-clock"></i>
                <div>
                    <div id="currentTime" class="time-display"></div>
                    <div id="currentDate" class="date-display"></div>
                </div>
            </div>
        </header>


    <form method="GET" action="admin.php" class="form-group">
        <label for="filtro_data" style="font-size: 18px">Filtrar por data:</label>
        <input type="date" id="filtro_data" name="data" value="<?php echo htmlspecialchars($dataFiltro); ?>">
        <button type="submit" class="submit-button" style="width: 100px; height: 30px; font-size: 17px";>Filtrar</button>
    </form>

    <div class="export-options">
        <a href="?exportar=csv&data=<?php echo htmlspecialchars($dataFiltro); ?>" class="export-btn">
            <i class="fas fa-file-csv" style="font-size: 17px"></i> Exportar para CSV
        </a>
    </div>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Crácha do funcionário</th>
                <th>Tipo</th>
                <th>Data e Hora</th>
                <!-- Adicione mais colunas conforme necessário -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['cracha']; ?></td>
                    <td><?php echo $row['tipo']; ?></td>
                    <td><?php echo $row['horario']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script>
      flatpickr("#filtro_data", {
        dateFormat: "Y-m-d",
        locale: "pt",
        allowInput: true
      });
    </script>
    <div class="paginacao">
    <?php if ($pagina > 1): ?>
        <a href="?pagina=<?php echo $pagina - 1; ?>&data=<?php echo $dataFiltro; ?>">Anterior</a>
    <?php endif; ?>

    Página <?php echo $pagina; ?> de <?php echo $totalPaginas; ?>

    <?php if ($pagina < $totalPaginas): ?>
        <a href="?pagina=<?php echo $pagina + 1; ?>&data=<?php echo $dataFiltro; ?>">Próxima</a>
    <?php endif; ?>
</div>
</body>
</html>
