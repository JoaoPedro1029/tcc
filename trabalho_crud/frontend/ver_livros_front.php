<?php
session_start();
include '../conexao.php'; // Inclui a conexão com o banco

// Verificar se o usuário está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: login_front.php");
    exit();
}

// Processar remoção de livro
if (isset($_GET['remover'])) {
    $id = $_GET['remover'];
    include '../backend/remover_livro.php'; // Inclui o script de remoção
    // Recarregar a página após a remoção
    header("Location: ver_livros_front.php");
    exit();
}

// Buscar livros diretamente do banco
try {
    $sql = "SELECT id, nome_livro, nome_autor, isbn FROM livro ORDER BY nome_livro";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar livros: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Livros</title>
    <link rel="icon" href="../imagens/1748908346791.png" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../estilos/style.css">
    <link rel="stylesheet" href="../estilos/ver.css">
</head>

<body>

    <!-- Link para voltar ao painel -->
    <div class="mt-3 text-start">
        <a href="../../" class="link-back">< Voltar para o painel</a>
    </div>

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

    <!-- Botão para cadastrar livro -->
  

    <!-- Conteúdo principal -->
      
    <div class="container">
        <h2 class="text-center">Lista de Livros</h2>
        
                <div class="text-end mb-3">
                <a href="cadastrar_livros_front.php" class="btn">Cadastrar Livro</a>
            </div>
        <div class="table-container">
            <table id="livrosTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>ISBN</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($livros as $livro): ?>
                        <tr>
                            <td class="scrollable-cell"><?php echo htmlspecialchars($livro['nome_livro']); ?></td>
                            <td class="scrollable-cell"><?php echo htmlspecialchars($livro['nome_autor']); ?></td>
                            <td class="scrollable-cell"><?php echo htmlspecialchars($livro['isbn']); ?></td>
                            <td class="scrollable-cell">
                                <a href="editar_livro_front.php?id=<?php echo $livro['id']; ?>" class="edit-link">Editar</a>
                                <a href="?remover=<?php echo $livro['id']; ?>" class="delete-link" onclick="return confirm('Tem certeza de que deseja remover este livro?')">Remover</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts de dependências -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Link para arquivos JS -->
    <script src="../interatividade/script.js"></script>
    <script src="../interatividade/devtools_block.js"></script>
    <script src="../interatividade/logout.js"></script>
    <script src="../interatividade/ver.js"></script>

</body>

</html>
