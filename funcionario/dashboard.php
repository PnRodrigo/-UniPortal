<?php
session_start();
require_once '../db_connection.php';

// RF1.2 - Controlo de Acesso por perfil (Apenas Staff/Grupo 3)
if (!isset($_SESSION['user_login']) || $_SESSION['user_grupo'] != 3) {
    header("Location: ../login/login.php");
    exit();
}

// Procurar estatísticas para o dashboard
$totalMatriculasPendentes = $pdo->query("SELECT COUNT(*) FROM matriculas WHERE estado = 'Pendente'")->fetchColumn();
$totalPautas = $pdo->query("SELECT COUNT(DISTINCT uc_id, ano_letivo, epoca) FROM pautas")->fetchColumn();

$page_title = "Painel de Controlo - UniPortal";
include '../includes/header.php';
?>

<div class="container">
    <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;">Gestão Académica</h1>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Bem-vindo, <strong>
                    <?php echo htmlspecialchars($_SESSION['user_login']); ?>
                </strong>.</p>
        </div>
        <div style="text-align: right; color: var(--text-muted); font-size: 0.85rem;">
            <i class="far fa-calendar-alt"></i>
            <?php echo date('d/m/Y'); ?>
        </div>
    </div>

    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <div class="card"
            style="border-left: 4px solid var(--warning); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span
                    style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Matrículas
                    Pendentes</span>
                <div style="font-size: 2rem; font-weight: 800; color: var(--text-main);">
                    <?php echo $totalMatriculasPendentes; ?>
                </div>
            </div>
            <i class="fas fa-clock" style="font-size: 2rem; color: rgba(245, 158, 11, 0.2);"></i>
        </div>

        <div class="card"
            style="border-left: 4px solid var(--primary); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span
                    style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Pautas
                    Ativas</span>
                <div style="font-size: 2rem; font-weight: 800; color: var(--text-main);">
                    <?php echo $totalPautas; ?>
                </div>
            </div>
            <i class="fas fa-file-invoice" style="font-size: 2rem; color: rgba(99, 102, 241, 0.2);"></i>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
        <div class="card" style="transition: transform 0.2s; cursor: default;">
            <div
                style="background: rgba(99, 102, 241, 0.05); border: 1px solid var(--border-color); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                <i class="fas fa-user-check" style="color: var(--primary); font-size: 1.2rem;"></i>
            </div>
            <h3 style="margin-bottom: 0.75rem; font-weight: 700;">Validar Matrículas</h3>
            <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 1.5rem;">Consulte a lista de pedidos de
                inscrição, verifique os dados dos alunos e aprove ou rejeite as solicitações com observações.</p>
            <a href="matriculas_admin.php" class="btn btn-primary" style="width: fit-content;">
                Abrir Listagem <i class="fas fa-arrow-right" style="margin-left: 0.5rem; font-size: 0.8rem;"></i>
            </a>
        </div>

        <div class="card" style="transition: transform 0.2s; cursor: default;">
            <div
                style="background: rgba(16, 185, 129, 0.05); border: 1px solid var(--border-color); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                <i class="fas fa-graduation-cap" style="color: var(--success); font-size: 1.2rem;"></i>
            </div>
            <h3 style="margin-bottom: 0.75rem; font-weight: 700;">Lançamento de Notas</h3>
            <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 1.5rem;">Crie pautas por Unidade
                Curricular e Época de Exame. O sistema filtra automaticamente os alunos inscritos para facilitar o
                registo.</p>
            <a href="pautas.php" class="btn btn-primary" style="width: fit-content;">
                Gerir Pautas <i class="fas fa-arrow-right" style="margin-left: 0.5rem; font-size: 0.8rem;"></i>
            </a>
        </div>
    </div>
</div>
</body>

</html>