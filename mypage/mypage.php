<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../sidebar/sidebar.css">
    <title>My Page</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    // ログアウトボタンがクリックされた場合
    if (isset($_POST['logout'])) {
        // セッションを破棄
        $_SESSION = array();
        session_destroy();

        // ログインページにリダイレクト
        header('Location: ../login/login.php');
        exit();
    }
    ?>
    <!-- サイドバー -->
    <?php include '../sidebar/sidebar.php'; ?>
    <div class="main-content">
    <div class="container">
        <!-- ログアウトボタン -->
        <form method="post">
            <button type="submit" name="logout">ログアウト</button>
        </form>
    </div>
</body>
</html>