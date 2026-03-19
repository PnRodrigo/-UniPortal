<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['user_login']) || $_SESSION['user_grupo'] != 1) {
    header("Location: ../login/login.php");
    exit();
}

// Processar Decisão
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['decisao'])) {
    $id = $_POST['ficha_id'];
    $estado = $_POST['decisao'];
    $obs = trim($_POST['observacoes']);
    $validado_por = $_SESSION['user_login'];

    $stmt = $pdo->prepare("UPDATE fichas_aluno SET estado = ?, observacoes = ?, validado_por = ? WHERE id = ?");
    $stmt->execute([$estado, $obs, $validado_por, $id]);
    header("Location: fichas_validar.php?status=atualizado");
    exit();
}

$fichas = $pdo->query("
    SELECT f.*, c.nome_c, u.login 
    FROM fichas_aluno f
    JOIN cursos c ON f.curso_id = c.id
    JOIN users u ON f.user_login = u.login
    ORDER BY (f.estado = 'Submetida') DESC, f.data_submissao DESC
")->fetchAll();

$page_title = "Validação de Fichas - UniPortal";
include '../includes/header.php';
?>

<div class="container">
    <div class="card">
        <h2 style="margin-bottom: 1.5rem;"><i class="fas fa-search-plus"></i> Fichas por Validar</h2>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Aluno</th>
                        <th>Curso Pretendido</th>
                        <th>Estado</th>
                        <th>Ações / Histórico</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fichas as $f): ?>
                        <tr>
                            <td>
                                <img src="../<?php echo $f['foto']; ?>"
                                    style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border-color);">
                            </td>
                            <td>
                                <div style="font-weight: 600;">
                                    <?php echo htmlspecialchars($f['nome_completo']); ?>
                                </div>
                                <small style="color: var(--text-muted);">
                                    <?php echo $f['login']; ?>
                                </small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($f['nome_c']); ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($f['estado']); ?>">
                                    <?php echo $f['estado']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($f['estado'] == 'Submetida'): ?>
                                    <form method="POST"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; min-width: 250px;">
                                        <input type="hidden" name="ficha_id" value="<?php echo $f['id']; ?>">
                                        <textarea name="observacoes" placeholder="Notas internas ou motivo de rejeição..."
                                            style="padding: 0.5rem; border-radius: 6px; border: 1px solid var(--border-color); font-size: 0.8rem;"></textarea>
                                        <div style="display: flex; gap: 0.4rem;">
                                            <button type="submit" name="decisao" value="Aprovada" class="btn"
                                                style="background: var(--success); color: white; flex: 1; padding: 0.4rem;">Aprovar</button>
                                            <button type="submit" name="decisao" value="Rejeitada" class="btn"
                                                style="background: var(--danger); color: white; flex: 1; padding: 0.4rem;">Rejeitar</button>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                                        <strong>Validado por:</strong>
                                        <?php echo $f['validado_por']; ?><br>
                                        <strong>Obs:</strong>
                                        <?php echo htmlspecialchars($f['observacoes']); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>

</html>