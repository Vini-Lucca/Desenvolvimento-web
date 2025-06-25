<?php
// ATENÇÃO: Verifique se a pasta TCPDF está em relatorios/tcpdf/

// Inicia a sessão para garantir que o acesso é autenticado
session_start();
if (!isset($_SESSION['usuario_id'])) {
    die('Acesso negado. Por favor, faça login.');
}

require_once '../config/conexao.php';
// Inclui a biblioteca TCPDF
require_once 'tcpdf/tcpdf.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['curso_id'])) {
    $curso_id = $_POST['curso_id'];

    // Busca os dados do curso e das matrículas
    $sql_curso = "SELECT nome_curso FROM cursos WHERE id = :curso_id";
    $stmt_curso = $pdo->prepare($sql_curso);
    $stmt_curso->execute([':curso_id' => $curso_id]);
    $curso = $stmt_curso->fetch(PDO::FETCH_ASSOC);

    if (!$curso) {
        die("Curso não encontrado.");
    }

    $nome_curso = $curso['nome_curso'];

    $sql_matriculas = "SELECT a.nome_completo AS aluno, p.nome_completo AS professor, m.status 
                       FROM matriculas m
                       JOIN alunos a ON m.aluno_id = a.id
                       JOIN professores p ON m.professor_id = p.id
                       WHERE m.curso_id = :curso_id
                       ORDER BY a.nome_completo";
    $stmt_matriculas = $pdo->prepare($sql_matriculas);
    $stmt_matriculas->execute([':curso_id' => $curso_id]);
    $matriculas = $stmt_matriculas->fetchAll(PDO::FETCH_ASSOC);

    // CRIAÇÃO DO PDF
    // Estende a classe TCPDF para criar Header e Footer personalizados
    class MYPDF extends TCPDF {
        public function Header() {
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 15, 'Sistema de Gestão Acadêmica', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            $this->Ln(5);
            $this->SetFont('helvetica', 'I', 10);
            $this->Cell(0, 15, 'Relatório de Matrículas', 0, false, 'C', 0, '', 0, false, 'M', 'M');
            $this->Ln(5);
            $this->Line(10, 25, $this->getPageWidth() - 10, 25);
        }

        public function Footer() {
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            $this->Cell(0, 10, 'Gerado em: ' . date('d/m/Y H:i:s'), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
    }

    // Cria um novo documento PDF
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Define informações do documento
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Seu Nome');
    $pdf->SetTitle('Relatório de Matrículas - ' . $nome_curso);
    
    // Define header e footer
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    // Adiciona uma página
    $pdf->AddPage();

    // Título do Relatório
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Curso: ' . $nome_curso, 0, 1, 'L');
    $pdf->Ln(5);

    // Cabeçalho da Tabela
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Cell(80, 7, 'Aluno', 1, 0, 'C', 1);
    $pdf->Cell(70, 7, 'Professor Responsável', 1, 0, 'C', 1);
    $pdf->Cell(30, 7, 'Status', 1, 1, 'C', 1);

    // Dados da Tabela
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFillColor(245, 245, 245);
    $fill = 0;
    foreach ($matriculas as $matricula) {
        $pdf->Cell(80, 6, $matricula['aluno'], 'LR', 0, 'L', $fill);
        $pdf->Cell(70, 6, $matricula['professor'], 'LR', 0, 'L', $fill);
        $pdf->Cell(30, 6, $matricula['status'], 'LR', 1, 'C', $fill);
        $fill = !$fill;
    }
    // Linha de fechamento da tabela
    $pdf->Cell(180, 0, '', 'T');


    // Fecha e gera o documento PDF
    ob_end_clean(); // Limpa qualquer saída de buffer anterior para evitar erro no PDF
    $pdf->Output('relatorio_' . str_replace(' ', '_', strtolower($nome_curso)) . '.pdf', 'I');

} else {
    die("Parâmetros inválidos para gerar o relatório.");
}
?>
