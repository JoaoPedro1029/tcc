<?php
// session_start();
include '../backend/ver_professores.php';

// Obter os professores da sessão
$professores = isset($_SESSION['professores']) ? $_SESSION['professores'] : array();
unset($_SESSION['professores']); // Limpar a sessão após usar
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professores</title>
    <link rel="icon" href="../imagens/1748908346791.png" type="image/x-icon">
    
    <!-- Link para o CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link para o CSS do DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- Link para o CSS personalizado -->
    <link rel="stylesheet" type="text/css" href="../estilos/style.css">
    <link rel="stylesheet" type="text/css" href="../estilos/ver.css">

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

    <!-- Voltar ao painel -->
    <div class="mt-3 text-start">
        <a href="../../" class="link-back">
            < Voltar para o painel</a>
    </div>

    <div class="mt-3 text-start">
        <a href="../../" class="link-back responsive-link">
            < Voltar</a>
    </div>


    <!-- Cadastrar professor -->

    <div class="container mt-5">
        <h2 class="text-center">Lista de Professores</h2>
        <div class="mt-3 text-end">
        <a href="cadastrar_professor_front.php" class="btn">Cadastrar Professor</a>
    </div>

        <div class="table-container">
            <table id="emprestimosTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>CPF</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Função para formatar CPF
                    function formatarCPF($cpf)
                    {
                        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
                    }

                    foreach ($professores as $professor): ?>
                        <tr>
                            <td class='scrollable-cell'><?php echo htmlspecialchars($professor['nome']); ?></td>
                            <td class='scrollable-cell'><?php echo htmlspecialchars($professor['email']); ?></td>
                            <td class='scrollable-cell'><?php echo formatarCPF($professor['cpf']); ?></td>
                            <td class='scrollable-cell'><a href='editar_prof_front.php?id=<?php echo $professor['id']; ?>' class='edit-link'>Editar</a></td>
                            <td class='scrollable-cell'><a href='?remover=<?php echo $professor['id']; ?>' class='delete-link' onclick='return confirm("Tem certeza de que deseja remover este professor?")'>Remover</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

        <!-- Link para arquivos JS -->
        <script src="../interatividade/script.js"></script>
        <script src="../interatividade/devtools_block.js"></script>
        <script src="../interatividade/logout.js"></script>
        <script src="../interatividade/ver.js"></script>

</body>

</html>
