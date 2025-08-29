<?php
session_start();
include '../backend/editar_livro.php'; // Inclui o script de backend para editar livro
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Livro</title>
    <link rel="icon" href="../imagens/1748908346791.png" type="image/x-icon">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../estilos/style.css">
    <link rel="stylesheet" type="text/css" href="../estilos/registrar.css">
</head>

<body>
    <!-- Cabeçalho -->
    <nav class="header">
        <a href="../../" class="header-link">
            <img src="../imagens/1748908346791.png" alt="Logo" class="header-logo" />
            <span class="header-text">Biblioteca M.V.C </span>
        </a>
        <span id="toggleSidebar" class="openbtn" onclick="toggleNav()">&#9776;</span>
    </nav>

    <!-- Menu lateral -->
    <div class="sidebar" id="mySidebar">
        <ul>
            <li><a href="relatorios_front.php">Relatórios</a></li>
            <li><a href="../backend/logout.php" id="logoutLink">Logout</a></li>
        </ul>
    </div>

    <!-- Voltar -->
    <div class="mt-3 text-start">
        <a href="../../" class="link-back">< Voltar para o painel</a>
    </div>

    <div class="container">
        <h2>Editar Livro</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $livro['id']; ?>">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título do Livro:</label>
                <input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo htmlspecialchars($livro['nome_livro']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="autor" class="form-label">Nome do Autor:</label>
                <input type="text" id="autor" name="autor" class="form-control" value="<?php echo htmlspecialchars($livro['nome_autor']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN:</label>
                <input type="text" id="isbn" name="isbn" class="form-control" value="<?php echo htmlspecialchars($livro['isbn']); ?>">
            </div>
            <button type="submit" class="btn">Salvar Alterações</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../interatividade/script.js"></script>
</body>

</html>
