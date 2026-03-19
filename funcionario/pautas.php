<?php
session_start();
require_once '../db_connection.php';

if (!isset($_SESSION['user_login']) || $_SESSION['user_grupo'] != 3) {
    header("Location: ../login/login.php");
    exit();
}

// RF5.3 - Processar Lançamento de Nota
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['lancar_nota'])) {
    $uc_id = $_POST['uc_id'];
    $aluno_login = $_POST['aluno_login'];
    $nota = $_POST['nota'];
    $epoca = $_POST['epoca'];
    $ano_letivo = $_POST['ano_letivo'];
    $responsavel = $_SESSION['user_login'];

    // Upsert (Se existir atualiza, senão insere)
    $check = $pdo->prepare("SELECT id FROM pautas WHERE uc_id = ? AND aluno_login = ? AND epoca = ? AND ano_letivo = ?");
    $check->execute([$uc_id, $aluno_login, $epoca, $ano_letivo]);
    $existing = $check->fetch();

    if ($existing) {
        $stmt = $pdo->prepare("UPDATE pautas SET nota = ?, responsavel = ? WHERE id = ?");
        $stmt->execute([$nota, $responsavel, $existing['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO pautas (uc_id, aluno_login, nota, epoca, ano_letivo, responsavel) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$uc_id, $aluno_login, $nota, $epoca, $ano_letivo, $responsavel]);
    }
    // Manter os filtros após o refresh
    header("Location: pautas.php?uc_id=$uc_id&ano=$ano_letivo&epoca=" . urlencode($epoca));
    exit();
}

// Obter dados para os filtros
$ucs = $pdo->query("SELECT * FROM disciplinas ORDER BY nome_d ASC")->fetchAll();
$selected_uc = $_GET['uc_id'] ?? null;
$selected_ano = $_GET['ano'] ?? date('Y') . '/' . (date('Y') + 1);
$selected_epoca = $_GET['epoca'] ?? 'Normal';

// RF5.2 - Obter alunos elegíveis (que tenham matrícula aprovada para o curso daquela UC)
$alunos = [];
if ($selected_uc) {
    $stmt = $pdo->prepare("
        SELECT DISTINCT u.login, f.nome_completo
        FROM users u
        JOIN fichas_aluno f ON u.login = f.user_login
        JOIN matriculas m ON u.login = m.aluno_login
        JOIN plano_estudos pe ON m.curso_id = pe.curso_id
        WHERE pe.disciplina_id = ? AND m.estado = 'Aprovado'
    ");
    $stmt->execute([$selected_uc]);
    $alunos = $stmt->fetchAll();
}

$page_title = "Pautas de Avaliação - UniPortal";
include '../includes/header.php';
?>

<div class="container">
    <div class="card" style="margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1.5rem;"><i class="fas fa-edit" style="color: var(--primary);"></i> Configurar Pauta
        </h2>
        <form method="GET"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">
            <div>
                <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">Unidade
                    Curricular</label>
                <select name="uc_id" required
                    style="width: 100%; padding: 0.6rem; border-radius: 8px; border: 1px solid var(--border-color);">
                    <option value="">Selecione...</option>
                    <?php foreach ($ucs as $uc): ?>
                        <option value="<?php echo $uc['id']; ?>" <?php echo ($selected_uc == $uc['id']) ? 'selected' : ''; ?>
                            >
                            <?php echo htmlspecialchars($uc['nome_d']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">Ano Letivo</label>
                <input type="text" name="ano" value="<?php echo htmlspecialchars($selected_ano); ?>"
                    style="width: 100%; padding: 0.6rem; border-radius: 8px; border: 1px solid var(--border-color);">
            </div>
            <div>
                <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted);">Época</label>
                <select name="epoca"
                    style="width: 100%; padding: 0.6rem; border-radius: 8px; border: 1px solid var(--border-color);">
                    <option <?php echo ($selected_epoca == 'Normal') ? 'selected' : ''; ?>>Normal</option>
                    <option <?php echo ($selected_epoca == 'Recurso') ? 'selected' : ''; ?>>Recurso</option>
                    <option <?php echo ($selected_epoca == 'Especial') ? 'selected' : ''; ?>>Especial</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 0.7rem;">Carregar Alunos</button>
        </form>
    </div>

    <?php if ($selected_uc && !empty($alunos)): ?>
        <div class="card">
            <h3>Lista de Alunos Elegíveis</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nome do Aluno</th>
                        <th>Nota Atual</th>
                        <th>Lançar / Editar Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno):
                        // Buscar nota se já existir
                        $st = $pdo->prepare("SELECT nota FROM pautas WHERE uc_id = ? AND aluno_login = ? AND epoca = ? AND ano_letivo = ?");
                        $st->execute([$selected_uc, $aluno['login'], $selected_epoca, $selected_ano]);
                        $nota_atual = $st->fetchColumn();
                        ?>
                        <tr>
                            <td>
                                <strong>
                                    <?php echo htmlspecialchars($aluno['nome_completo']); ?>
                                </strong><br>
                                <small>
                                    <?php echo $aluno['login']; ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($nota_atual !== false): ?>
                                    <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--primary); font-size: 1rem;">
                                        <?php echo number_format($nota_atual, 2); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-style: italic;">Não lançada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                                    <input type="hidden" name="uc_id" value="<?php echo $selected_uc; ?>">
                                    <input type="hidden" name="aluno_login" value="<?php echo $aluno['login']; ?>">
                                    <input type="hidden" name="epoca" value="<?php echo $selected_epoca; ?>">
                                    <input type="hidden" name="ano_letivo" value="<?php echo $selected_ano; ?>">
                                    <input type="number" name="nota" step="0.01" min="0" max="20" placeholder="0.00" required
                                        style="width: 80px; padding: 0.4rem; border-radius: 6px; border: 1px solid var(--border-color);">
                                    <button type="submit" name="lancar_nota" class="btn btn-primary"
                                        style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Gravar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($selected_uc): ?>
        <div class="card" style="text-align: center; color: var(--text-muted);">
            Nenhum aluno elegível encontrado para esta UC e Curso com matrícula aprovada.
        </div>
    <?php endif; ?>
</div>
</body>

</html>