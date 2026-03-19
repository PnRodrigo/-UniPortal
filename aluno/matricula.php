<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['user_login']) || $_SESSION['user_grupo'] != 2) {
    header("Location: ../login/login.php");
    exit();
}

$user = $_SESSION['user_login'];

// RF4 - Verificar se a ficha está Aprovada antes de permitir matrícula
$stmt = $pdo->prepare("SELECT estado FROM fichas_aluno WHERE user_login = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$user]);
$ficha = $stmt->fetch();

$pode_matricular = ($ficha && $ficha['estado'] == 'Aprovada');

// Processar Pedido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $pode_matricular) {
    $curso_id = $_POST['curso_id'];
    $stmt = $pdo->prepare("INSERT INTO matriculas (aluno_login, curso_id, estado) VALUES (?, ?, 'Pendente')");
    $stmt->execute([$user, $curso_id]);
    header("Location: dashboard.php?msg=sucesso_matricula");
    exit();
}

$cursos = $pdo->query("SELECT * FROM cursos WHERE ativo = 1")->fetchAll();
$page_title = "Pedido de Matrícula - UniPortal";
include '../includes/header.php';
?>

<div class="container" style="max-width: 600px;">
    <div class="card">
        <h2 style="margin-bottom: 1rem;"><i class="fas fa-pen-nib" style="color: var(--primary);"></i> Matrícula</h2>

        <?php if (!$pode_matricular): ?>
            <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); color: var(--warning); padding: 1.5rem; border-radius: 12px; text-align: center;">
                <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                <p><strong>Atenção:</strong> Precisa de ter a sua <strong>Ficha de Aluno aprovada</strong> pelo Gestor
                    Pedagógico antes de poder efetuar um pedido de matrícula.</p>
                <a href="ficha_aluno.php" class="btn btn-primary" style="margin-top: 1rem;">Verificar Minha Ficha</a>
            </div>
        <?php else: ?>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Selecione o curso no qual deseja formalizar a sua
                inscrição.</p>
            <form method="POST">
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Curso</label>
                    <select name="curso_id" required
                        style="width: 100%; padding: 0.8rem; border-radius: 10px; border: 1px solid var(--border-color);">
                        <option value="">Selecione o curso...</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?php echo $c['id']; ?>">
                                <?php echo htmlspecialchars($c['nome_c']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"
                    style="width: 100%; justify-content: center; padding: 1rem; font-size: 1.1rem;">
                    Submeter Pedido de Matrícula
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>

</html>