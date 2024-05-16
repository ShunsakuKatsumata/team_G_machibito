<!DOCTYPE html>
<html lang="ja">

<head>
    <?php session_start(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="New_User.css">
    <style>
        input[type="text"],input[type="email"], input[type="password"] {
            color: white; /* テキスト入力とパスワード入力のフォント色を白に設定 */
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="New_User.php" method="POST">
            <h2>ユーザー登録</h2>
            <div class="form-group">
                <input type="text" id="username" name="username" placeholder="username" required>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="email" required>
                <input type="password" id="password" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>" placeholder="password" required>
            </div>
            <input type="submit" class="new_user" value="登録">
            <div class="login">
                <a href="../login/login.php">サインインする</a>
            </div>
        </form>
    </div>

    <script>
        const New_user = document.querySelector('.new_user');

        New_user.addEventListener('click', (event) => {
        localStorage.setItem('loginMessage', '新規登録しました');
        });
    </script>

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
