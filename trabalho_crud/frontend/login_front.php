<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="../imagens/1748908346791.png" type="image/x-icon">

    <link rel="stylesheet" href="../estilos/login.css">
    <link rel="stylesheet" href="../estilos/painel.css"> 
</head>

<body class="body">

    <div class="text-center mb-3">
        <img src="../imagens/1748908346791.png" alt="Logo da Biblioteca M.V.C" class="logo">
    </div>

    <footer style="text-align: center; font-size: 29px; position: absolute; top: 270px; padding: 0px; color: black;">
        <p>Gestão da Biblioteca</p>
    </footer>

    <div class="container">

        <div class="text-center">
            <h1 class="h1">Login do Professor</h1>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo htmlspecialchars($erro); ?>
            </div>
        <?php endif; ?>

        <form action="../backend/login.php" method="POST">
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" name="cpf" id="cpf" class="form-control" autocomplete="on" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control" autocomplete="off" required>
            </div>
            <button type="submit" class="btn">Entrar</button> <!-- Updated button class -->
        </form>

    </div>

    <div class="mensagem-topo">
        <p>
            <strong>Ainda não possui uma conta? Use:</strong><br>
            <strong>CPF:</strong> 000.000.000-00<br>
            <strong>Senha:</strong> senha123
        </p>
    </div>

    <?php
    session_start();
    if (!empty($_SESSION['mensagem_login'])) {
        echo '<div class="mensagem-login" id="mensagemLogin">' . htmlspecialchars($_SESSION['mensagem_login']) . '</div>';
        unset($_SESSION['mensagem_login']);
    }
    ?>

    <footer style="text-align: center; position: fixed; bottom: 0; color: black;">
        <p>&copy; 2025 João Pedro.</p>
    </footer>

    <script>
        document.getElementById('cpf').addEventListener('input', function(e) {
            let cpf = e.target.value.replace(/\D/g, ''); // Remove tudo que não for número
            cpf = cpf.replace(/^(\d{3})(\d)/, '$1.$2');
            cpf = cpf.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
            cpf = cpf.replace(/\.(\d{3})(\d)/, '.$1-$2');
            e.target.value = cpf.substring(0, 14); // Limita a 14 caracteres (XXX.XXX.XXX-XX)
        });

        setTimeout(function() {
            var mensagem = document.getElementById('mensagemLogin');
            if (mensagem) {
                mensagem.style.transition = 'opacity 0.5s ease';
                mensagem.style.opacity = '0';
                setTimeout(function() {
                    mensagem.remove(); // Remove o elemento do DOM
                }, 500); // Aguarda o tempo da transição para remover
            }
        }, 2000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
