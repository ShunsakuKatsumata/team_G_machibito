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
        $user_id = $_SESSION['user']['user_id'];

        $dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
        $username = 'kobe';
        $password = 'denshi';

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // ユーザーIDと一致するタイトルを取得
            $sql = "SELECT post_id, title FROM post WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // 削除ボタンがクリックされた場合
            if (isset($_POST['delete'])) {
                $post_id = $_POST['post_id'];

                // トランザクション開始
                $pdo->beginTransaction();

                try {
                    // 対応するpostを削除
                    $sql = "DELETE FROM post WHERE post_id = :post_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
                    $stmt->execute();

                    // 対応するreplyを削除
                    $sql = "DELETE FROM reply WHERE post_id = :post_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
                    $stmt->execute();

                    // トランザクションコミット
                    $pdo->commit();

                    // 同じページにリダイレクト
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit();
                } catch (PDOException $e) {
                    // エラーが発生した場合はロールバック
                    $pdo->rollBack();
                    echo "エラー：" . $e->getMessage();
                }
            }
            // ログアウトボタンがクリックされた場合
            if (isset($_POST['logout'])) {
                // セッションを破棄
                $_SESSION = array();
                session_destroy();

                // ログインページにリダイレクト
                header('Location: ../login/login.php');
                exit();
            }
        } catch (PDOException $e) {
            echo "エラー：" . $e->getMessage();
        }
    ?>
    <!-- サイドバー -->
    <?php include '../sidebar/sidebar.php'; ?>
    <div class="main-content">
    <div class="container">
        <?php 
        // ユーザー名
        echo $_SESSION['user']['user_name'];
        echo '<br>';
        // タイトルと削除ボタンを表示
        foreach ($posts as $post) {
            echo $post['title'];
            echo '<form method="post">';
            echo '<input type="hidden" name="post_id" value="' . $post['post_id'] . '">';
            echo '<button type="submit" name="delete">削除</button>';
            echo '</form>';
            echo '<br>';
        }

        
        ?>
        <!-- ログアウトボタン -->
        <form method="post">
            <button type="submit" name="logout">ログアウト</button>
        </form>
    </div>
</body>
</html>