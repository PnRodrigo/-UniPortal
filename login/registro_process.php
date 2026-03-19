<?php
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']);
    $pwd = $_POST['pwd'];
    // Sempre registar como Aluno (grupo 2) para segurança via web
    $grupo = 2;

    // Verificar se utilizador já existe
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = ?");
    $check->execute([$login]);
    if ($check->fetchColumn() > 0) {
        header("Location: registro.php?erro=1");
        exit();
    }

    $hash = password_hash($pwd, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (login, pwd, grupo) VALUES (?, ?, ?)");

    if ($stmt->execute([$login, $hash, $grupo])) {
        header("Location: login.php?sucesso=1");
    } else {
        header("Location: registro.php?erro=unknown");
    }
}
