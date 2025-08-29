<?php
session_start();
include '../conexao.php';

$erro = '';
$sucesso = '';
$token = $_GET['token'] ?? '';

if (!$token) {
    die("Token inválido.");
}

// Verifica se token existe e não expirou
$sql = "SELECT id FROM professor WHERE token_recuperacao = ? AND token_expiracao > NOW()";
$stmt = $pdo->prepare($sql);
$stmt->execute([$token]);
$professor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$professor) {
    die("Token inválido ou expirado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha = $_POST['senha'] ?? '';
    $confirma = $_POST['confirma'] ?? '';

    if (strlen($senha) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres.";
    } elseif ($senha !== $confirma) {
        $erro = "As senhas não coincidem.";
    } else {
        // Atualiza senha e remove token
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql_update = "UPDATE professor SET senha = ?, token_recuperacao = NULL, token_expiracao = NULL WHERE id = ?";
        $stmt_update = $pdo->prepare($sql_update);
        if ($stmt_update->execute([$hash, $professor['id']])) {
            $sucesso = "Senha redefinida com sucesso! Você já pode fazer login.";
        } else {
            $erro = "Erro ao atualizar a senha.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="body">
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h2 class="text-center mb-4">Redefinir Senha</h2>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div>
        <a href="login.php" class="btn btn-primary w-100">Ir para Login</a>
    <?php else: ?>
        <form action="resetar_senha.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <div class="mb-3">
                <label for="senha" class="form-label">Nova senha</label>
                <input type="password" id="senha" name="senha" class="form-control" required minlength="6" autofocus>
            </div>
            <div class="mb-3">
                <label for="confirma" class="form-label">Confirme a nova senha</label>
                <input type="password" id="confirma" name="confirma" class="form-control" required minlength="6">
            </div>
            <button type="submit" class="btn btn-primary w-100">Redefinir Senha</button>
        </form>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
