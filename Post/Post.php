<?php
// フォームが送信されたかどうかを確認する
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送信されたデータを取得する
    $title = $_POST['title'];
    $content = $_POST['content'];
    $tag = $_POST['tag'];

    // データベースに接続する
    $dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
    $username = 'kobe';
    $password = 'denshi';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // データを挿入するクエリを準備する
        $sql = "INSERT INTO post (title, content, tag) VALUES (:title, :content, :tag)";
        $stmt = $pdo->prepare($sql);

        // パラメータをバインドする
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':tag', $tag, PDO::PARAM_STR);

        // クエリを実行する
        $stmt->execute();

        // 投稿一覧画面にリダイレクトする
        header("Location: ../Display/Display.php");
        exit();
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
    }

    // データベース接続を閉じる
    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <link rel="stylesheet" href="Post.css">
    <title>投稿画面</title>
</head>

<body>
    <!-- サイドバー設定 -->
    <?php include '../sidebar/sidebar.php'; ?>

    <div class="error-message"></div>
    <div class="post-container">
        <form action="post.php" method="post">
            <div class="user-info">
                <div class="user-icon"></div>
                <input type="text" name="title" placeholder="タイトルを入力してください" class="title-input">
            </div>
            <textarea name="content" placeholder="本文を入力してください" class="content-input"></textarea>
            <textarea name="tag" placeholder="タグを入力して下さい。例）#Python #資格" class="tag-input"></textarea>
            <div class="button-container">
                <button type="button" class="clear-button">削除する</button>
                <button type="submit" class="post-button">投稿する</button>
            </div>
        </form>
    </div>
    <script>
        const clearButton = document.querySelector('.clear-button');
        const titleInput = document.querySelector('.title-input');
        const contentInput = document.querySelector('.content-input');
        const tagInput = document.querySelector('.tag-input');
        const errorMessage = document.querySelector('.error-message');

        clearButton.addEventListener('click', () => {
            titleInput.value = '';
            contentInput.value = '';
            tagInput.value = '';
            hideErrorMessage();
        });

        function hideErrorMessage() {
            errorMessage.classList.remove('show-message');
        }

        function showErrorMessage(message) {
            errorMessage.textContent = message;
            errorMessage.classList.add('show-message');
            setTimeout(() => {
                errorMessage.classList.remove('show-message');
            }, 3000);
        }
    </script>
</body>

</html>