<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
    </style>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $new_password_conf = $_POST['new_password_conf'];

    // データベース接続
    $dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
    $username = 'kobe';
    $password = 'denshi';

    $pdo = new PDO($dsn, $username, $password);

    ?>
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <meta charset="UTF-8">
    <title>パスワード変更</title>
</head>
<body>
    <h1>パスワード変更</h1>
    <form action="pass_change.php" method="post">
        <label for="old_password">古いパスワード</label>
        <br>
        <input type="password" name="old_password" id="old_password">
        <label for="new_password">新しいパスワード</label>
        <br>
        <input type="password" name="new_password" id="new_password">
        <label for="new_password_conf">新しいパスワード（確認）</label>
        <input type="password" name="new_password_conf" id="new_password_conf">
        <button type="submit">変更</button>
    </form>
    <!-- サイドバー -->
    <?php include '../sidebar/sidebar.php'; ?>
</body>
</html>

