<?php
include '../backend/cadastrar_livros.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Livros</title>
    <link rel="icon" href="../imagens/1748908346791.png" type="image/x-icon">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
        <a href="../../" class="link-back">
            < Voltar para o painel</a>
    </div>

    <!-- Mensagem de feedback -->
    <div class="mensagem">
        <?php
        if (isset($_SESSION['mensagem_livro'])) {
            echo $_SESSION['mensagem_livro'];
            unset($_SESSION['mensagem_livro']);
        }
        ?>
    </div>

    <!-- Container para o formulário -->
    <div class="container">
        <h2>Buscar Livros</h2>
        <form method="POST" action="">
            <label for="termo_busca">Digite o Título ou ISBN do Livro:</label>
            <input type="text" id="termo_busca" name="termo_busca" autocomplete="off" required>
            <button type="submit" class="btn">Buscar</button>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal" id="resultadosModal" tabindex="-1" aria-labelledby="resultadosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultadosModalLabel">Resultados da Busca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <?php if (isset($_SESSION['resultados']) && !empty($_SESSION['resultados']['items'])): ?>
                            <?php foreach ($_SESSION['resultados']['items'] as $index => $item): ?>
                                <li>
                                    <img src="<?php echo isset($item['volumeInfo']['imageLinks']['thumbnail']) ? $item['volumeInfo']['imageLinks']['thumbnail'] : 'Imagem não disponível'; ?>" alt="Capa do Livro">
                                    <div>
                                        <strong><?php echo $item['volumeInfo']['title']; ?></strong>
                                        <p>Autor(a): <?php echo isset($item['volumeInfo']['authors']) ? implode(', ', $item['volumeInfo']['authors']) : 'Autor desconhecido'; ?></p>
                                        <p>ISBN: <?php echo isset($item['volumeInfo']['industryIdentifiers'][0]['identifier']) ? $item['volumeInfo']['industryIdentifiers'][0]['identifier'] : 'ISBN não disponível'; ?></p>
                                        <form method="POST" action="">
                                            <input type="hidden" name="adicionar_livro_id" value="<?php echo $index; ?>">
                                            <button type="submit" class="modal-btn">Adicionar Livro</button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>
                                <p>Nenhum resultado encontrado.</p>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Div para inserir livro manualmente -->
    <div class="container" style="margin-top: 50px;">
        <h3>Não encontrou o livro? Insira os dados manualmente:</h3>
        <form method="POST" action="">
            <label for="manual_titulo">Título do Livro:</label>
            <input type="text" id="manual_titulo" name="manual_titulo" autocomplete="off" required>

            <label for="manual_autor">Nome do(a) Autor(a):</label>
            <input type="text" id="manual_autor" name="manual_autor" autocomplete="off" required>

            <button type="submit" name="adicionar_manual" class="btn">Adicionar Manualmente</button>
        </form>
    </div>

    <!-- Botão para ver livros cadastrados -->
        <a href="ver_livros_front.php" class="btn">Ver Livros Cadastrados</a>
    



    <script>
        // Verifica se o modal deve ser aberto
        <?php if (isset($_SESSION['modalAberto']) && $_SESSION['modalAberto']): ?>
            var myModal = new bootstrap.Modal(document.getElementById('resultadosModal'));
            myModal.show();
            // Limpa a flag da sessão após abrir o modal
            <?php unset($_SESSION['modalAberto']); ?>
        <?php endif; ?>
    </script>

    <!-- Link para arquivos JS -->
    <script src="../interatividade/script.js"></script>
    <script src="../interatividade/devtools_block.js"></script>
    <script src="../interatividade/logout.js"></script>

</body>

</html>