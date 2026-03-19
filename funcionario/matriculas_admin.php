<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['user_login']) || $_SESSION['user_grupo'] != 3) {
    header("Location: ../login/login.php");
    exit();
}

// RF4.2 - Processar Decisão do Funcionário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['decisao'])) {
    $id = $_POST['matricula_id'];
    $novo_estado = $_POST['decisao']; // Pega 'Aprovado' ou 'Rejeitado' do botão
    $obs = trim($_POST['observacoes']);
    $responsavel = $_SESSION['user_login'];

    $stmt = $pdo->prepare("UPDATE matriculas SET estado = ?, observacoes = ?, responsavel = ?, data_decisao = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->execute([$novo_estado, $obs, $responsavel, $id]);

    header("Location: matriculas_admin.php?msg=sucesso");
    exit();
}

// Listar todos os pedidos (RF4.2 - Listar pendentes primeiro)
$matriculas = $pdo->query("
    SELECT m.*, c.nome_c, u.login, f.nome_completo
    FROM matriculas m
    JOIN cursos c ON m.curso_id = c.id
    JOIN users u ON m.aluno_login = u.login
    LEFT JOIN fichas_aluno f ON u.login = f.user_login
    ORDER BY (m.estado = 'Pendente') DESC, m.id DESC
")->fetchAll();

$page_title = "Gestão de Matrículas - UniPortal";
include '../includes/header.php';
?>

<div class="container">
    <div class="card">
        <h2 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-clipboard-list" style="color: var(--primary);"></i> Pedidos de Matrícula
        </h2>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Aluno</th>
                        <th>Curso</th>
                        <th>Data Pedido</th>
                        <th>Estado</th>
                        <th>Ações / Decisão</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matriculas as $m): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--text-muted);">#
                                <?php echo $m['id']; ?>
                            </td>
                            <td>
                                <div style="font-weight: 600;">
                                    <?php echo htmlspecialchars($m['nome_completo'] ?? $m['login']); ?>
                                </div>
                                <small style="color: var(--text-muted);">
                                    <?php echo $m['login']; ?>
                                </small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($m['nome_c']); ?>
                            </td>
                            <td>
                                <?php echo isset($m['data_pedido']) ? date('d/m/Y H:i', strtotime($m['data_pedido'])) : 'N/A'; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo strtolower($m['estado']); ?>">
                                    <?php echo $m['estado']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($m['estado'] == 'Pendente'): ?>
                                    <form method="POST"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; min-width: 200px;">
                                        <input type="hidden" name="matricula_id" value="<?php echo $m['id']; ?>">
                                        <textarea name="observacoes" placeholder="Adicionar observações..."
                                            style="padding: 0.5rem; border-radius: 8px; border: 1px solid var(--border-color); font-family: inherit; font-size: 0.85rem; resize: vertical;"></textarea>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <button type="submit" name="decisao" value="Aprovado" class="btn"
                                                style="background: var(--success); color: white; flex: 1; padding: 0.4rem; font-size: 0.8rem;">Aprovar</button>
                                            <button type="submit" name="decisao" value="Rejeitado" class="btn"
                                                style="background: var(--danger); color: white; flex: 1; padding: 0.4rem; font-size: 0.8rem;">Rejeitar</button>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <div style="font-size: 0.85rem; color: var(--text-muted);">
                                        <strong>Responsável:</strong>
                                        <?php echo $m['responsavel']; ?><br>
                                        <?php if ($m['observacoes']): ?>
                                            <strong>Obs:</strong>
                                            <?php echo htmlspecialchars($m['observacoes']); ?>
                                        <?php endif; ?>
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