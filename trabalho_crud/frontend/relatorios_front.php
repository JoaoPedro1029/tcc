<?php
include '../backend/relatorios.php';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Relatórios</title>
  <link rel="icon" href="../imagens/1748908346791.png" type="image/x-icon">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="../estilos/style.css">
  <link rel="stylesheet" type="text/css" href="../estilos/relatorios.css">
  <script src="https://www.gstatic.com/charts/loader.js"></script>

  <script>
    google.charts.load('current', {
      packages: ['corechart']
    });
    google.charts.setOnLoadCallback(drawCharts);

    const temAlunos = <?php echo $temAlunos ? 'true' : 'false'; ?>;
    const temLivros = <?php echo $temLivros ? 'true' : 'false'; ?>;
    const temSeries = <?php echo $temSeries ? 'true' : 'false'; ?>;

    function drawCharts() {
      if (temAlunos) drawAlunosChart();
      if (temLivros) drawLivrosChart();
      if (temSeries) drawSeriesChart();
    }

    function drawAlunosChart() {
      var data = google.visualization.arrayToDataTable([
        ['Aluno', 'Livros Lidos', {
          role: 'annotation'
        }],
        <?php
        if ($temAlunos) {
          foreach ($alunosData as $row) {
            $nome = addslashes($row['aluno_nome']);
            $total = (int)$row['total'];
            echo "['$nome', $total, '$total'],";
          }
        }
        ?>
      ]);

      var options = {
        titleTextStyle: {
          fontSize: 24
        },
        title: 'Top 5 Alunos que mais leram',
        legend: {
          position: 'none'
        },
        chartArea: {
          width: '70%'
        },
        height: 400,
        width: 900,
        hAxis: {
          minValue: 0,
          textStyle: {
            color: 'transparent'
          }, // oculta os números
          gridlines: {
            color: 'transparent'
          } // oculta as linhas de grade
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('graficoAlunos'));
      chart.draw(data, options);
    }

    function drawLivrosChart() {
      var data = google.visualization.arrayToDataTable([
        ['Livro', 'Leituras', {
          role: 'annotation'
        }],
        <?php
        if ($temLivros) {
          foreach ($livrosData as $row) {
            $nome = addslashes($row['nome_livro']);
            $total = (int)$row['total'];
            echo "['$nome', $total, '$total'],";
          }
        }
        ?>
      ]);

      var options = {
        titleTextStyle: {
          fontSize: 24
        },
        title: 'Top 5 Livros mais lidos',
        legend: {
          position: 'none'
        },
        chartArea: {
          width: '70%'
        },
        height: 400,
        width: 900,
        hAxis: {
          minValue: 0,
          textStyle: {
            color: 'transparent'
          }, // oculta os números
          gridlines: {
            color: 'transparent'
          } // oculta as linhas de grade
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('graficoLivros'));
      chart.draw(data, options);
    }

    function drawSeriesChart() {
      var data = google.visualization.arrayToDataTable([
        ['Série', 'Leituras', {
          role: 'annotation'
        }],
        <?php
        if ($temSeries) {
          foreach ($seriesData as $row) {
            $nome = addslashes($row['serie']);
            $total = (int)$row['total'];
            echo "['$nome', $total, '$total'],";
          }
        }
        ?>
      ]);

      var options = {
        titleTextStyle: {
          fontSize: 24
        },
        title: 'Top 5 Turmas que mais leram',
        legend: {
          position: 'none'
        },
        chartArea: {
          width: '70%'
        },
        height: 400,
        width: 900,
        hAxis: {
          minValue: 0,
          textStyle: {
            color: 'transparent'
          }, // oculta os números
          gridlines: {
            color: 'transparent'
          } // oculta as linhas de grade
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('graficoSeries'));
      chart.draw(data, options);
    }
  </script>
</head>

<body>
  <!-- Cabeçalho -->
  <nav class="header">
    <a href="../../" class="header-link">
      <img src="../imagens/1748908346791.png" alt="Logo" class="header-logo" />
      <span class="header-text">Biblioteca M.V.C </span>
    </a>
  </nav>

  <!-- Voltar à página anterior -->
  <div class="mt-3 text-start">
    <a href="#" onclick="goBack()" class="link-back">< Voltar</a>
  </div>

  <script>
    function goBack() {
      if (document.referrer) {
        history.back();
      } else {
        window.location.href = '../../';
      }
    }
  </script>


  <div class="d-flex">
    <div class="relatorios-container flex-grow-1">
      <div id="graficoAlunos-container">
        <div id="graficoAlunos" class="grafico"></div>
        <?php if (!$temAlunos): ?>
          <div id="semDadosAlunos" class="alert alert-warning">Nenhum dado de empréstimo devolvido encontrado para alunos.</div>
        <?php endif; ?>
      </div>

      <div id="graficoLivros-container">
        <div id="graficoLivros" class="grafico"></div>
        <?php if (!$temLivros): ?>
          <div id="semDadosLivros" class="alert alert-warning">Nenhum dado de empréstimo devolvido encontrado para livros.</div>
        <?php endif; ?>
      </div>

      <div id="graficoSeries-container">
        <div id="graficoSeries" class="grafico"></div>
        <?php if (!$temSeries): ?>
          <div id="semDadosSeries" class="alert alert-warning">Nenhum dado de empréstimo devolvido encontrado para séries.</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- BARRA LATERAL -->
    <div class="barra-lateral">
      <div class="lista-observacoes">
        <?php if ($temNotas): ?>
          <?php foreach ($notasData as $nota): ?>
            <div class="card">
              <button class="btn-excluir" data-id="<?php echo $nota['id']; ?>">x</button>
              <div class="card-header">
                <strong><?php echo htmlspecialchars($nota['professor_nome']); ?></strong>
                <span class="data-anotacao">
                  <?php echo date('d/m/Y - H:i', strtotime($nota['data_corrigida'])); ?>
                </span>
              </div>
              <div class="card-body">
                <p class="card-text"><?php echo nl2br(htmlspecialchars($nota['texto'])); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="alert alert-warning">Nenhuma anotação encontrada.</div>
        <?php endif; ?>
      </div>

      <button id="btnNovaAnotacao" class="btn">Nova Anotação</button>

      <div id="novaAnotacao" class="nova-anotacao" style="display: none;">
        <form id="formNovaAnotacao">
          <textarea name="texto" class="form-control mb-2" rows="4" placeholder="Escreva sua observação..." required></textarea>
          <div class="btn-group-center">
            <button type="submit" class="btn-sal">Salvar</button>
            <button type="button" id="btnCancelar" class="btn-can">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.getElementById('btnNovaAnotacao').addEventListener('click', function() {
    document.getElementById('novaAnotacao').style.display = 'block';
    this.style.display = 'none';
  });

  document.getElementById('btnCancelar').addEventListener('click', function() {
    document.getElementById('novaAnotacao').style.display = 'none';
    document.getElementById('btnNovaAnotacao').style.display = 'inline-block';
  });
</script>

<!-- Link para arquivos JS -->
<script src="../interatividade/devtools_block.js"></script>
<script src="../interatividade/anotacoes.js"></script>

</body>
</html>

<?php
$pdo = null;
?>
