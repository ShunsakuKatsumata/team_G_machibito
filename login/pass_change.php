<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>パスワード変更</title>
    <link rel="stylesheet" href="pass_change.css">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login/login.php');
    exit();
}

// データベース接続
$dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
$username = 'kobe';
$password = 'denshi';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'データベース接続失敗: ' . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $new_password_conf = $_POST['new_password_conf'];

    $stmt = $pdo->prepare('SELECT password FROM account WHERE user_id = ?');
    $stmt->execute([$_SESSION['user']['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        echo 'ユーザーが見つかりません。';
    } else {
        if (!password_verify($old_password, $row['password'])) {
            echo '古いパスワードが間違っています。';
        } elseif ($new_password !== $new_password_conf) {
            echo '新しいパスワードとその確認が一致しません。';
        } else {
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE account SET password = ? WHERE user_id = ?');
            $stmt->execute([$new_password_hashed, $_SESSION['user']['user_id']]);
            echo 'パスワードを変更しました。';
            header('Location: ../mypage/mypage.php');
            exit;
        }
    }
}
?>
</head>
<body>
    <h1>パスワード変更</h1>
    <form action="pass_change.php" method="post">
        <label for="old_password">古いパスワード</label>
        <input type="password" name="old_password" id="old_password">
        <label for="new_password">新しいパスワード</label>
        <input type="password" name="new_password" id="new_password">
        <label for="new_password_conf">新しいパスワード（確認）</label>
        <input type="password" name="new_password_conf" id="new_password_conf">
        <button type="submit">変更</button>
    </form>
    <!-- サイドバー -->
    <?php include '../sidebar/sidebar.php'; ?>
</body>
</html>
