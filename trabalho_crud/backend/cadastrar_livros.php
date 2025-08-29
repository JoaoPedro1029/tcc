<?php
session_start(); // Inicia a sessão

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: ../frontend/login_front.php");
    exit();
}

// Função para buscar livros na API do Google Books
function buscarLivros($termo) {
    // Limpa espaços extras
    $termo = trim($termo);

    // Limpa todos os caracteres não numéricos ou X/x
    $termo_limpo = preg_replace('/[^0-9Xx]/', '', $termo);

    // Decide se busca por ISBN ou título
    if (preg_match('/^\d{9}[\dXx]{1}$/', $termo_limpo) || preg_match('/^\d{13}$/', $termo_limpo)) {
        $consulta = "isbn:" . strtoupper($termo_limpo);
    } else {
        $consulta = "intitle:" . urlencode($termo);
    }

    $url = "https://www.googleapis.com/books/v1/volumes?q=" . $consulta;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $resposta = curl_exec($ch);
    if ($resposta === false) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        echo "Erro ao buscar livros: HTTP $http_code";
        return null;
    }

    $resultado = json_decode($resposta, true);
    if (isset($resultado['totalItems']) && $resultado['totalItems'] == 0) {
        echo "Nenhum resultado encontrado para o termo fornecido.";
        return null;
    }

    return $resultado;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['termo_busca'])) {
        $termo = $_POST['termo_busca'];
        $resultados = buscarLivros($termo);

        if ($resultados !== null) {
            $_SESSION['resultados'] = $resultados;
            $_SESSION['modalAberto'] = true;
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    } elseif (isset($_POST['adicionar_livro_id'])) {

        if (isset($_SESSION['resultados']) && isset($_SESSION['resultados']['items'])) {

            $livro_id = $_POST['adicionar_livro_id'];

            if (isset($_SESSION['resultados']['items'][$livro_id])) {

                $livro = $_SESSION['resultados']['items'][$livro_id]['volumeInfo'];

                include '../conexao.php';

                $titulo = isset($livro['title']) ? $livro['title'] : 'Título não disponível';
                $autor = isset($livro['authors']) ? implode(', ', $livro['authors']) : 'Autor desconhecido';
                $isbn = '';
                if (isset($livro['industryIdentifiers']) && count($livro['industryIdentifiers']) > 0) {
                    $isbn = $livro['industryIdentifiers'][0]['identifier'];
                }

                if (!empty($isbn)) {
                    $sql_check = "SELECT * FROM livro WHERE isbn = ? LIMIT 1";
                    $stmt_check = $pdo->prepare($sql_check);
                    $stmt_check->execute([$isbn]);
                    $result_check = $stmt_check->fetchAll();
                } else {
                    $sql_check = "SELECT * FROM livro WHERE nome_livro = ? AND nome_autor = ? LIMIT 1";
                    $stmt_check = $pdo->prepare($sql_check);
                    $stmt_check->execute([$titulo, $autor]);
                    $result_check = $stmt_check->fetchAll();
                }

                if (count($result_check) > 0) {
                    $_SESSION['mensagem_livro'] = "<p style='color: orange;'>Este livro já está cadastrado.</p>";
                } else {
                    $sql_insert = "INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES (?, ?, ?)";
                    $stmt_insert = $pdo->prepare($sql_insert);
                    
                    if ($stmt_insert->execute([$titulo, $autor, $isbn])) {
                        $_SESSION['mensagem_livro'] = "<p style='color: green;'>Livro adicionado com sucesso!</p>";
                    } else {
                        $errorInfo = $stmt_insert->errorInfo();
                        $_SESSION['mensagem_livro'] = "<p style='color: red;'>Erro ao adicionar o livro: " . $errorInfo[2] . "</p>";
                    }
                }

                $pdo = null;

            } else {
                $_SESSION['mensagem_livro'] = "<p style='color: red;'>Livro não encontrado nos resultados.</p>";
            }

        } else {
            $_SESSION['mensagem_livro'] = "<p style='color: red;'>Resultados da busca não estão disponíveis.</p>";
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    elseif (isset($_POST['manual_titulo']) && isset($_POST['manual_autor'])) {

        include '../conexao.php';

        $titulo = trim($_POST['manual_titulo']);
        $autor = trim($_POST['manual_autor']);
        $isbn = ''; // ISBN não é obrigatório no manual

        // Verifica se já existe esse livro
        $sql_check = "SELECT * FROM livro WHERE nome_livro = ? AND nome_autor = ? LIMIT 1";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$titulo, $autor]);
        $result_check = $stmt_check->fetchAll();

        if (count($result_check) > 0) {
            $_SESSION['mensagem_livro'] = "<p style='color: orange;'>Este livro já está cadastrado.</p>";
        } else {
            $sql_insert = "INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES (?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            
            if ($stmt_insert->execute([$titulo, $autor, $isbn])) {
                $_SESSION['mensagem_livro'] = "<p style='color: green;'>Livro adicionado com sucesso!</p>";
            } else {
                $errorInfo = $stmt_insert->errorInfo();
                $_SESSION['mensagem_livro'] = "<p style='color: red;'>Erro ao adicionar o livro: " . $errorInfo[2] . "</p>";
            }
        }

        $pdo = null;

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

}
?>
