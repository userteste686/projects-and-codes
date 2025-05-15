<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "empresaDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if(isset($_POST['nome']) || isset($_POST['senha'])) {

    if(strlen($_POST['nome']) == 0) {
        echo "Preencha com seu nome";
    } else if(strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {

        $nome = $conn->real_escape_string($_POST['nome']);
        $senha = $conn->real_escape_string($_POST['senha']);

        $sql = "SELECT * FROM administrador WHERE nome = '$nome' AND senha = '$senha'";
        $sql_query = $conn->query($sql) or die("Falha na execução do código SQL: " . $conn->error);

        $quantidade = $sql_query->num_rows;

        if($quantidade == 1) {
            
            $username = $sql_query->fetch_assoc();

            if(!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['admin_logado'] = true;

            header("Location: admin.php");

        } else {
            echo "Falha ao logar! Usuario ou senha incorretos";
        }

    }
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        
        h1 {
            margin-top: 0;
            color: #333;
        }
        
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        
        .input-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .recover-link {
            display: block;
            margin-bottom: 20px;
            color: #0066cc;
            text-decoration: none;
            font-size: 14px;
        }
        
        .login-button {
            background-color: #0066cc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }
        
        .login-button:hover {
            background-color: #0055aa;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>LOGIN</h1>
        
        <form action="" method="POST">
            <div class="input-group">
                <label for="nome">Usuario</label>
                <input type="text" id="usuario" name="nome">
            </div>
            
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha">
            </div>
            
            <a href="#" class="recover-link">Recuperar senha?</a>
            
            <button type="submit" class="login-button">Entrar</button>
        </form>
</body>
</html>