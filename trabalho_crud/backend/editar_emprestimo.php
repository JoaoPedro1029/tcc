<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: ../frontend/login_front.php");
    exit();
}

// Conexão com o banco
include '../conexao.php';

// Função para converter data de d/m/Y para Y-m-d
function converterDataParaBD($data)
{
    $partes = explode('/', $data);
    return $partes[2] . '-' . $partes[1] . '-' . $partes[0];
}

// Função para converter data de Y-m-d para d/m/Y
function converterDataParaBR($data)
{
    $partes = explode('-', $data);
    return $partes[2] . '/' . $partes[1] . '/' . $partes[0];
}

if (!isset($_GET['id'])) {
    $_SESSION['mensagem_editar_emprestimo'] = "<p style='color: red;'>ID do empréstimo não fornecido.</p>";
    header("Location: ../frontend/editar_emprestimo_front.php");
    exit();
}

$id_emprestimo = intval($_GET['id']);

// Se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluno = intval($_POST['id_aluno']);
    $id_livro = intval($_POST['id_livro']);
    $data_emprestimo = converterDataParaBD($_POST['data_emprestimo']);
    $data_devolucao = converterDataParaBD($_POST['data_devolucao']);

    if ($data_devolucao < $data_emprestimo) {
        $_SESSION['mensagem_editar_emprestimo'] = "<p style='color: red;'>A data de devolução não pode ser anterior à data de empréstimo.</p>";
        header("Location: ../frontend/editar_emprestimo_front.php?id=$id_emprestimo");
        exit();
    }

    $stmt = $pdo->prepare("UPDATE emprestimo SET id_aluno = ?, id_livro = ?, data_emprestimo = ?, data_devolucao = ? WHERE id = ?");

    if ($stmt->execute([$id_aluno, $id_livro, $data_emprestimo, $data_devolucao, $id_emprestimo])) {
        $_SESSION['mensagem_editar_emprestimo'] = "<p style='color: green;'>Empréstimo atualizado com sucesso!</p>";
    } else {
        $errorInfo = $stmt->errorInfo();
        $_SESSION['mensagem_editar_emprestimo'] = "<p style='color: red;'>Erro ao atualizar: " . $errorInfo[2] . "</p>";
    }

    $pdo = null;

    // Redireciona para o front para mostrar a mensagem
    header("Location: ../frontend/editar_emprestimo_front.php?id=$id_emprestimo");
    exit();
}

// Se não for POST, busca os dados do empréstimo para exibir no front (pode ser usado para carregar os dados inicialmente)
$stmt = $pdo->prepare("SELECT e.id, e.id_aluno, e.id_livro, e.data_emprestimo, e.data_devolucao, a.nome AS aluno_nome, l.nome_livro
                        FROM emprestimo e
                        JOIN aluno a ON e.id_aluno = a.id
                        JOIN livro l ON e.id_livro = l.id
                        WHERE e.id = ?");
$stmt->execute([$id_emprestimo]);
$emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$emprestimo) {
    $_SESSION['mensagem_editar_emprestimo'] = "<p style='color: red;'>Empréstimo não encontrado.</p>";
    header("Location: ../frontend/editar_emprestimo_front.php");
    exit();
}

$pdo = null;
?>
