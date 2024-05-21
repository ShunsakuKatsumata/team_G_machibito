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

    if (empty($old_password) || empty($new_password) || empty($new_password_conf)) {
        echo "<script>localStorage.removeItem('errorMessage_p_empty');localStorage.setItem('errorMessage_p_empty', '全てのフィールドに入力してください。');</script>";
    } else {
    $stmt = $pdo->prepare('SELECT password FROM account WHERE user_id = ?');
    $stmt->execute([$_SESSION['user']['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        echo 'ユーザーが見つかりません。';
    } else {
            if (!password_verify($old_password, $row['password'])) {
                echo "<script>localStorage.removeItem('errorMessage_p_old');localStorage.setItem('errorMessage_p_old', '古いパスワードが間違っています。');</script>";
            } elseif ($new_password !== $new_password_conf) {
                echo "<script>localStorage.removeItem('errorMessage_p_new');localStorage.setItem('errorMessage_p_new', '新しいパスワードとその確認が一致しません。');</script>";
            } else {
                $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE account SET password = ? WHERE user_id = ?');
                $stmt->execute([$new_password_hashed, $_SESSION['user']['user_id']]);
                echo "<script>localStorage.setItem('errorMessage_p_ok', '古いパスワードが間違っています。');</script>";
                header('Location: ../mypage/mypage.php');
                exit;
            }
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
    <!-- パスワード変更時空のメッセージ -->
    <div class="errorMessage_p_empty" id="errorMessage_p_empty"></div>
    <!-- パスワード変更時エラー古のメッセージ -->
    <div class="errorMessage_p_old" id="errorMessage_p_old"></div>

    <!-- パスワード変更時エラー新のメッセージ -->
    <div class="errorMessage_p_new" id="errorMessage_p_new"></div>

    <script>
        window.onload = function() {
            var errorMessage_p_old = localStorage.getItem('errorMessage_p_old');
            if (errorMessage_p_old) {
                var errorMessage_p_oldElement = document.getElementById('errorMessage_p_old');
                errorMessage_p_oldElement.innerText = errorMessage_p_old;
                errorMessage_p_oldElement.style.opacity = '1';
                setTimeout(function() {
                    errorMessage_p_oldElement.style.opacity = '0';
                }, 3000);
                // メッセージを表示した後は削除する
                localStorage.removeItem('errorMessage_p_old');
            }

            var errorMessage_p_new = localStorage.getItem('errorMessage_p_new');
            if (errorMessage_p_new) {
                var errorMessage_p_newElement = document.getElementById('errorMessage_p_new');
                errorMessage_p_newElement.innerText = errorMessage_p_new;
                errorMessage_p_newElement.style.opacity = '1';
                setTimeout(function() {
                    errorMessage_p_newElement.style.opacity = '0';
                }, 3000);
                // メッセージを表示した後は削除する
                localStorage.removeItem('errorMessage_p_new');
            }

            var errorMessage_p_empty = localStorage.getItem('errorMessage_p_empty');
            if (errorMessage_p_empty) {
                var errorMessage_p_emptyElement = document.getElementById('errorMessage_p_empty');
                errorMessage_p_emptyElement.innerText = errorMessage_p_empty;
                errorMessage_p_emptyElement.style.opacity = '1';
                setTimeout(function() {
                    errorMessage_p_emptyElement.style.opacity = '0';
                }, 3000);
                // メッセージを表示した後は削除する
                localStorage.removeItem('errorMessage_p_empty');
            }
        }
        </script>
</body>
</html>
