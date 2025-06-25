<?php
$page_title = 'Relatório de Matrículas por Curso';
require_once '../includes/header.php';
require_once '../config/conexao.php';

// Carrega lista de cursos para o formulário
$cursos = $pdo->query("SELECT id, nome_curso FROM cursos ORDER BY nome_curso ASC")->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Gerar Relatório de Matrículas por Curso</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-file-earmark-pdf me-2"></i>Selecione o Curso</h6>
        </div>
        <div class="card-body">
            <!-- O formulário aponta para o script que gera o PDF e abre em uma nova aba (target="_blank") -->
            <form action="gerar_pdf_matriculas.php" method="POST" target="_blank">
                <div class="row">
                    <div class="col-md-8">
                        <label for="curso_id" class="form-label">Curso</label>
                        <select name="curso_id" id="curso_id" class="form-select" required>
                            <option value="">Selecione um curso para gerar o relatório</option>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?php echo $curso['id']; ?>">
                                    <?php echo htmlspecialchars($curso['nome_curso']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                         <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-filetype-pdf me-2"></i>Gerar PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-muted">
            O relatório listará todos os alunos matriculados no curso selecionado, incluindo seus status e professores.
        </div>
    </div>
</div>


<?php require_once '../includes/footer.php'; ?>
