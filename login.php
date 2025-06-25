<?php
session_start();
require_once 'config(conexao bd).php';

// Se o usuário já estiver logado, redireciona para o index
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$erro = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        // Busca o usuário no banco de dados
        $sql = "SELECT id, nome, senha FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe e se a senha está correta
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Inicia a sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header("Location: index.php");
            exit;
        } else {
            $erro = "E-mail ou senha inválidos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - S.O-trabalho2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="card-body">
                <h2 class="text-center mb-4">
                    <i class="bi bi-person-circle me-2"></i>Acesso ao Sistema
                </h2>
                <p class="text-center text-muted mb-4">Usuário de teste: admin@sistema.com / senha123</p>
                
                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
