<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <link rel="stylesheet" href="mypage.css">
    <title>プロフィール</title>
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
                        echo '<p>投稿がありません</p>'; // なんにもなかったらなしと表示
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
                    if (empty($questions)) {
                        echo '<p>質問がありません</p>'; // なんにもなかったらなしと表示
                    } else {
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

    <footer id="footer">
    <p id="page-top"><a href="#">Page Top</a></p> 
    </footer>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://coco-factory.jp/ugokuweb/wp-content/themes/ugokuweb/data/8-1-2/js/8-1-2.js"></script>
