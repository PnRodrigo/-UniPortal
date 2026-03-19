<?php
session_start();
require_once '../db_connection.php';

// RF1.2 - Controlo de Acesso (Gestor = 1)
if (!isset($_SESSION['user_login']) || $_SESSION['user_grupo'] != 1) {
    header("Location: ../login/login.php");
    exit();
}

// Estatísticas
$totalCursos = $pdo->query("SELECT COUNT(*) FROM cursos")->fetchColumn();
$totalAlunos = $pdo->query("SELECT COUNT(*) FROM users WHERE grupo = 2")->fetchColumn();
$fichasPendentes = $pdo->query("SELECT COUNT(*) FROM fichas_aluno WHERE estado = 'Submetida'")->fetchColumn();

$page_title = "Administração Pedagógica - UniPortal";
include '../includes/header.php';
?>

<div class="container">
    <div style="margin-bottom: 2.5rem;">
        <h1 style="font-weight: 800; color: var(--text-main);">Painel de Gestão Pedagógica</h1>
        <p style="color: var(--text-muted);">Administre a estrutura académica e valide novos ingressos.</p>
    </div>

    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <div class="card" style="text-align: center;">
            <div style="color: var(--primary); font-size: 2rem; font-weight: 800;">
                <?php echo $totalCursos; ?>
            </div>
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">
                Cursos</div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="color: var(--primary); font-size: 2rem; font-weight: 800;">
                <?php echo $totalAlunos; ?>
            </div>
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">
                Alunos Registados</div>
        </div>
        <div class="card" style="text-align: center; border-bottom: 4px solid var(--warning);">
            <div style="color: var(--warning); font-size: 2rem; font-weight: 800;">
                <?php echo $fichasPendentes; ?>
            </div>
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">
                Fichas por Validar</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <div class="card">
            <i class="fas fa-graduation-cap" style="font-size: 1.5rem; color: var(--primary); margin-bottom: 1rem;"></i>
            <h3>Cursos e Disciplinas</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Faça a gestão do catálogo de
                cursos e das unidades curriculares (UC) disponíveis.</p>
            <div style="display: flex; gap: 0.5rem;">
                <a href="cursos.php" class="btn btn-primary" style="flex: 1; justify-content: center;">Cursos</a>
                <a href="ucs.php" class="btn btn-primary"
                    style="flex: 1; justify-content: center; background: transparent; border: 1px solid var(--border-color); color: var(--text-main);">Disciplinas</a>
            </div>
        </div>

        <div class="card">
            <i class="fas fa-layer-group" style="font-size: 1.5rem; color: var(--success); margin-bottom: 1rem;"></i>
            <h3>Planos de Estudo</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Associe as disciplinas aos
                respetivos cursos, definindo o ano e o semestre.</p>
            <a href="planos.php" class="btn btn-primary" style="width: 100%; justify-content: center;">Configurar
                Matrizes</a>
        </div>

        <div class="card">
            <i class="fas fa-user-check" style="font-size: 1.5rem; color: var(--warning); margin-bottom: 1rem;"></i>
            <h3>Validação de Ingressos</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Analise as fichas de aluno
                submetidas, valide as fotos e aprove os dados pessoais.</p>
            <a href="fichas_validar.php" class="btn btn-primary" style="width: 100%; justify-content: center;">Validar
                Fichas</a>
        </div>
    </div>
</div>
</body>

</html>