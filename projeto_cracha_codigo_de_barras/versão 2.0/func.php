<?php
session_start();

if (!isset($_SESSION['func_logado']) || $_SESSION['func_logado'] !== true) {
    header("Location: login_func.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Ponto Digital - Funcionário</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script defer src="funcionario.js"></script>
</head>
<body>
    <div class="app-container">
        <!-- Cabeçalho -->
        <header class="app-header">
            <div class="logo">
                <i class="fas fa-fingerprint"></i>
                <h1>Registro de Ponto</h1>
            </div>
            <div class="clock-container">
                <i class="far fa-clock"></i>
                <div>
                    <div id="currentTime" class="time-display"></div>
                    <div id="currentDate" class="date-display"></div>
                </div>
            </div>
        </header>

        <!-- Conteúdo Principal -->
        <main class="main-content">
            <!-- Seção de Registro -->
            <section class="registration-section">
                <div class="card">
                    <h2><i class="fas fa-clock"></i> Registrar Ponto</h2>
                    
                    <form id="pontoForm" action="process.php" method="POST">
                        <div class="form-group">
                            <label for="cracha"><i class="fas fa-id-card"></i> Número do Crachá:</label>
                            <div class="input-with-button">
                                <input type="text" id="cracha" name="cracha" required placeholder="Digite ou escaneie o número">
                                <button type="button" id="abrirCamera" class="scan-button">
                                    <i class="fas fa-camera"></i> Escanear
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tipo"><i class="fas fa-exchange-alt"></i> Tipo de Registro:</label>
                            <select id="tipo" name="tipo" required>
                                <option value="" disabled selected>Selecione o tipo</option>
                                <option value="Entrada">Entrada</option>
                                <option value="Saida">Saída</option>
                                <option value="Intervalo">Intervalo</option>
                            </select>
                        </div>

                        <button type="submit" class="submit-button">
                            <i class="fas fa-check-circle"></i> Registrar Ponto
                        </button>
                    </form>
                    <div class="admin-link">
                        <h2><a href="logout.php"><i class="fas fa-user-shield"></i> Sair</a></h2>
                    </div>
                </div>
            </section>
        </main>

        <!-- Janela da Câmera (hidden by default) -->
        <div id="cameraContainer" class="camera-modal">
            <div class="camera-content">
                <h3><i class="fas fa-camera"></i> Posicione o código de barras na área destacada</h3>
                
                <div class="scanner-container">
                    <div class="quagga-overlay">
                        <div class="viewport">
                            <video id="camera" autoplay playsinline></video>
                            <div class="scan-box"></div>
                            <div class="scan-line"></div>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="fecharCamera" class="close-camera">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <footer class="app-footer">
        <p>Sistema de Registro de Ponto Digital &copy; <span id="currentYear"></span></p>
    </footer>

<script>
  async function startCamera() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      alert("Seu navegador não suporta acesso à câmera ou a página não está em um ambiente seguro (HTTPS ou localhost).");
      return;
    }

    try {
      const stream = await navigator.mediaDevices.getUserMedia({ video: true });
      document.getElementById('camera').srcObject = stream;
    } catch (error) {
      alert("Erro ao acessar a câmera: " + error.message);
      console.error(error);
    }
  }

  startCamera();
</script>
</body>
</html>