<?php
session_start(); // セッションを開始

// セッションを破棄
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
session_destroy(); // セッションを完全に破棄

// ログインページまたはホームページにリダイレクト
header("Location: ../login/login.php");
exit;
