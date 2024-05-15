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
    // フォームからのデータを取得
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // データベースに接続
            $db_host = 'localhost';
            $db_user = 'kobe';
            $db_password = 'denshi';
            $db_name = 'post';
            $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

            // 接続をチェック
            if ($conn->connect_error) {
                die("データベース接続エラー: " . $conn->connect_error);
            }

            // データベースに挿入する前に既存のメールアドレスをチェック
            $check_email_sql = "SELECT COUNT(*) AS count FROM account WHERE email = ?";
            $check_stmt = $conn->prepare($check_email_sql);
            $check_stmt->bind_param('s', $email);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                echo "エラー: このメールアドレスは既に登録されています。";
            } else {
                // データベースに挿入する準備
                $insert_sql = "INSERT INTO account (user_name, email, password) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param('sss', $username, $email, $password);
                
                // クエリを実行して結果を確認
                if ($insert_stmt->execute()) {
                    
                    header("Location: ../login/login.php");
                    exit; // リダイレクト後にスクリプトの実行を終了
                } else {
                    echo "エラー: " . $insert_sql . "<br>" . $conn->error;
                }
            }

            // データベース接続を閉じる
            $conn->close();
        } else {
            echo "すべてのフィールドを入力してください。";
        }
    }
    ?>
</body>

</html>