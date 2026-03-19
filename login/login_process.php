<?php
session_start();
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login']);
    $pwd = $_POST['pwd'];

    if (empty($login) || empty($pwd)) {
        header("Location: login.php?erro=1");
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user) {
        // Verifica hash da senha (suporta MD5 antigo para compatibilidade inicial, mas prefere password_verify)
        $valid = false;
        if (password_verify($pwd, $user['pwd'])) {
            $valid = true;
        } elseif ($user['pwd'] === md5($pwd)) {
            // Se for MD5, atualiza logo para password_hash
            $valid = true;
            $newHash = password_hash($pwd, PASSWORD_DEFAULT);
            $upd = $pdo->prepare("UPDATE users SET pwd = ? WHERE login = ?");
            $upd->execute([$newHash, $login]);
        }

        if ($valid) {
            $_SESSION['user_login'] = $user['login'];
            $_SESSION['user_grupo'] = $user['grupo'];
            header("Location: ../index.php");
            exit();
        }
    }

    header("Location: login.php?erro=1");
    exit();
}