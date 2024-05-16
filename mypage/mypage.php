<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../sidebar/sidebar.css">
    <title>My Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin-left: 200px; /* サイドバーの幅を考慮 */
        }
        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 1.8em;
            margin-right: 20px;
        }
        .action-buttons {
            display: flex;
            align-items: center;
        }
        .action-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        .action-button:hover {
            background-color: #2980b9;
        }
        .content {
            display: flex;
            width: 100%;
            max-width: 1200px;
            justify-content: space-between;
        }
        .post-card, .question-card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin: 10px;
            flex: 1;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .item {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .item h3 {
            margin: 0;
            font-size: 1.2em;
        }
        .item form {
            margin-top: 10px;
        }
        .item button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .item button:hover {
            background-color: #c0392b;
        }
        .main-content {
            padding: 20px;
            width: 100%;
        }
    </style>
</head>
<body>
    <?php
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../login/login.php');
            exit();
        }
        $user_id = $_SESSION['user']['user_id'];

        $dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
        $username = 'kobe';
        $password = 'denshi';

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // ユーザーIDと一致する記事のタイトルを取得
            $sql = "SELECT post_id, title FROM post WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 記事削除ボタンがクリックされた場合
            if (isset($_POST['delete_post'])) {
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

            // ユーザーIDと一致する質問のタイトルを取得
            $sql = "SELECT ident, title FROM question_post WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 質問の削除ボタンがクリックされた場合
            if (isset($_POST['delete_question'])) {
                $ident = $_POST['ident'];

                // トランザクション開始
                $pdo->beginTransaction();

                try {
                    // 対応するquestion_postを削除
                    $sql = "DELETE FROM question_post WHERE ident = :ident";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':ident', $ident, PDO::PARAM_INT);
                    $stmt->execute();

                    // 対応するquestion_answerを削除
                    $sql = "DELETE FROM question_answer WHERE post_id = :ident";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':ident', $ident, PDO::PARAM_INT);
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

            // パスワード変更ボタンがクリックされた場合
            if (isset($_POST['pass_change'])) {
                // パスワード変更ページにリダイレクト
                header('Location: ../login/pass_change.php');
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
            <div class="header">
                <div class="title"><?php echo $_SESSION['user']['user_name']; ?></div>
                <div class="action-buttons">
                    <!-- パスワード変更ボタン -->
                    <form method="post" style="margin: 0;">
                        <button type="submit" name="pass_change" class="action-button">パスワード変更</button>
                    </form>
                    <!-- ログアウトボタン -->
                    <form method="post" style="margin: 0;">
                        <button type="submit" name="logout" class="action-button">ログアウト</button>
                    </form>
                </div>
            </div>
            <div class="content">
                <div class="post-card">
                    <h2>記事一覧</h2>
                    <?php 
                    if (empty($posts)) {
                        echo '<p>なし</p>'; // なんにもなかったらなしと表示
                    } else {
                        foreach ($posts as $post) {
                            echo '<div class="item">';
                            echo '<h3><a href="../Post_Detail/Post_Detail.php?post_id=' . $post['post_id'] . '">' . $post['title'] . '</a></h3>';
                            echo '<form method="post">';
                            echo '<input type="hidden" name="post_id" value="' . $post['post_id'] . '">';
                            echo '<button type="submit" name="delete_post">削除</button>';
                            echo '</form>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
                <div class="question-card">
                    <h2>質問一覧</h2>
                    <?php 
                    if (!empty($questions)) {
                        foreach ($questions as $question) {
                            echo '<div class="item">';
                            echo '<h3><a href="../comment/comment_detail.php?ident=' . $question['ident'] . '">' . $question['title'] . '</a></h3>';
                            echo '<form method="post">';
                            echo '<input type="hidden" name="ident" value="' . $question['ident'] . '">';
                            echo '<button type="submit" name="delete_question">削除</button>';
                            echo '</form>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
