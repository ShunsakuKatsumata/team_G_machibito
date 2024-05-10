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
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="登録">
        </form>
    </div>
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

            // データベースに挿入する準備
            $sql = "INSERT INTO account (user_name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $username, $email, $password);

            // クエリを実行して結果を確認
            if ($stmt->execute()) {
                echo "ユーザーが登録されました。";


                
                header("Location: ../Display/Display.php");
            } else {
                echo "エラー: " . $sql . "<br>" . $conn->error;
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
