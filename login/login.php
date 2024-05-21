<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    session_start();
    ?>
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <link rel="stylesheet" href="login.css">
    <title>ログイン</title>
    <style>
        input[type="text"], input[type="password"] {
            color: white; /* テキスト入力とパスワード入力のフォント色を白に設定 */
        }
    </style>
</head>

<?php
// データベース接続情報
$dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
$username = 'kobe';
$password = 'denshi';

try {
    // PDOインスタンスを作成
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ログインフォームが送信されたかどうかを確認
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // ユーザーが送信したログイン情報を取得
        $email = $_POST['email'];
        $password = $_POST['password'];

        // 入力されたメールアドレスとパスワードを持つユーザーを検索
        $stmt = $pdo->prepare("SELECT * FROM account WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        // ユーザーが見つかり、ハッシュ化したパスワードが一致する場合はログイン成功
        if ($user && password_verify($password, $user['password'])) {
            // ユーザー情報をセッションに保存
            $_SESSION['user'] = $user;
            header('Location: ../mypage/mypage.php');
            exit;
        } else {
            echo "<script>localStorage.setItem('errorMessage', 'ログインに失敗しました');</script>";
        }
    }
} catch (PDOException $e) {
    echo "接続失敗: " . $e->getMessage();
}
?>

<body>
    <div class="main-content">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h2>ログイン</h2>
            <div>
                <input type="text" name="email" placeholder="email" required>
                <input type="password" name="password" placeholder="password" required>
            </div>
            <div>
                <input type="submit" value="ログイン">
            </div>
            <div class="new">
                <a href="../New_User/New_User.php">新規登録はこちら</a>
            </div>
        </form>
    </div>
    <!-- ログイン時のメッセージ -->
    <div class="login-message" id="loginMessage"></div>
    <!-- ログイン失敗時のメッセージ -->
    <div class="errorMessage" id="errorMessage"></div>
    <script>
        window.onload = function() {
            var loginMessage = localStorage.getItem('loginMessage');
            if (loginMessage) {
                var loginMessageElement = document.getElementById('loginMessage');
                loginMessageElement.innerText = loginMessage;
                loginMessageElement.style.opacity = '1';
                setTimeout(function() {
                    loginMessageElement.style.opacity = '0';
                }, 3000);
                // メッセージを表示した後は削除する
                localStorage.removeItem('loginMessage');
            }
            
            var errorMessage = localStorage.getItem('errorMessage');
            if (errorMessage) {
                var errorMessageElement = document.getElementById('errorMessage');
                errorMessageElement.innerText = errorMessage;
                errorMessageElement.style.opacity = '1';
                setTimeout(function() {
                    errorMessageElement.style.opacity = '0';
                }, 3000);
                // メッセージを表示した後は削除する
                localStorage.removeItem('errorMessage');
            }
        }
    </script>
</body>

</html>