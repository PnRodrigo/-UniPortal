<?php
session_start();
require_once '../db_connection.php';

// RF1.2 - Controlo de Acesso (Perfil Aluno = 2)
if (!isset($_SESSION['user_login']) || $_SESSION['user_grupo'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

$user = $_SESSION['user_login'];

// Procurar estado da ficha de aluno
$stmt = $pdo->prepare("SELECT * FROM fichas_aluno WHERE user_login = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$user]);
$ficha = $stmt->fetch();

// Procurar pedidos de matrícula
$stmt = $pdo->prepare("
    SELECT m.*, c.nome_c 
    FROM matriculas m 
    JOIN cursos c ON m.curso_id = c.id 
    WHERE m.aluno_login = ? 
    ORDER BY m.id DESC
");
$stmt->execute([$user]);
$matriculas = $stmt->fetchAll();

$page_title = "Área do Aluno - UniPortal";
include '../includes/header.php';
?>

<div class="container">
    <div style="margin-bottom: 2.5rem;">
        <h1 style="font-weight: 800; color: var(--text-main);">Olá,
            <?php echo htmlspecialchars($user); ?>!
        </h1>
        <p style="color: var(--text-muted);">Bem-vindo ao seu portal académico. Verifique o estado dos seus processos
            abaixo.</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">

        <div class="card">
            <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-id-card" style="color: var(--primary);"></i> Ficha de
                Aluno</h3>
            <?php if ($ficha): ?>
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <img src="../<?php echo $ficha['foto']; ?>"
                        style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--border-color);">
                    <div style="margin-top: 1rem; font-weight: 700;">
                        <?php echo htmlspecialchars($ficha['nome_completo']); ?>
                    </div>
                    <span class="badge badge-<?php echo strtolower($ficha['estado']); ?>"
                        style="margin-top: 0.5rem; display: inline-block;">
                        <?php echo $ficha['estado']; ?>
                    </span>
                </div>
                <p style="font-size: 0.9rem; color: var(--text-muted);">
                    <?php echo $ficha['observacoes'] ? 'Obs: ' . htmlspecialchars($ficha['observacoes']) : 'A aguardar validação pedagógica.'; ?>
                </p>
            <?php else: ?>
                <div style="text-align: center; padding: 1rem;">
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Ainda não preencheu a sua ficha de aluno.</p>
                </div>
            <?php endif; ?>
            <a href="ficha_aluno.php" class="btn btn-primary"
                style="width: 100%; justify-content: center; margin-top: 1rem;">
                <?php echo $ficha ? 'Editar/Ver Ficha' : 'Preencher Agora'; ?>
            </a>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0;"><i class="fas fa-university" style="color: var(--success);"></i> Minhas
                    Matrículas</h3>
                <a href="matricula.php" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Nova
                    Inscrição</a>
            </div>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Estado</th>
                            <th>Data/Decisão</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($matriculas)): ?>
                            <tr>
                                <td colspan="3" style="text-align:center; color: var(--text-muted);">Nenhum pedido efetuado.
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($matriculas as $m): ?>
                            <tr>
                                <td style="font-weight: 600;">
                                    <?php echo htmlspecialchars($m['nome_c']); ?>
                                </td>
                                <td><span class="badge badge-<?php echo strtolower($m['estado']); ?>">
                                        <?php echo $m['estado']; ?>
                                    </span></td>
                                <td style="font-size: 0.85rem; color: var(--text-muted);">
                                    <?php echo $m['data_decisao'] ? date('d/m/Y', strtotime($m['data_decisao'])) : 'Pendente'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>

</html>