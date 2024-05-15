<!DOCTYPE html>
<html lang="ja">

<head>
    <?php session_start(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="New_User.css">
</head>

<body>
    <div class="container">
        <h2>ユーザー登録</h2>
        <form action="New_User.php" method="POST">
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>" required>
            </div>
            <input type="submit" value="登録">
        </form>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // データベースに接続
            $dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
            $db_username = 'kobe';
            $db_password = 'denshi';

            try {
                $pdo = new PDO($dsn, $db_username, $db_password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // データベースに挿入する準備
                $stmt = $pdo->prepare("INSERT INTO account (user_name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $password]);

                echo "ユーザーが登録されました。";

                // ログインページにリダイレクト
                header("Location: ../login/login.php");
                exit;
            } catch (PDOException $e) {
                echo "エラー: " . $e->getMessage();
            }
        } else {
            echo "すべてのフィールドを入力してください。";
        }
    }
    ?>

</body>

</html>
