<?php
session_start();
// フォームが送信されたかどうかを確認する
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送信されたデータを取得する
    $title = $_POST['title'];
    $content = $_POST['content'];
    $tag = $_POST['tag'];

    // フォームの入力値が空でないかをチェックする
    if (empty($title) || empty($content) || empty($tag)) {
        echo "タイトル・内容・タグを入力して下さい";
    } else {
        // タグ入力欄から入力されたタグを取得
        $tags_input = $_POST['tag'];

        // タグを半角スペースと全角スペースで区切り、配列に分割
        $tags_array = preg_split('/[\s　]+/u', $tags_input);


        // 各タグに対してトリミングを行い、空のタグを削除
        $tags_array = array_map('trim', $tags_array);
        $tags_array = array_filter($tags_array);

        // タグをカンマで連結して文字列にする
        $tags_combined = implode(",", $tags_array);

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
            $stmt->bindParam(':tag', $tags_combined, PDO::PARAM_STR);

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
            <textarea name="tag" placeholder="タグを入力して下さい　　例）Python　C言語" class="tag-input"></textarea>
            <div class="button-container">
                <!-- 投稿方法の詳細ポップアップ -->
                <div id="popup" class="popup">
                    <div class="popup-content">
                        <span class="close-popup" onclick="closePopup()">&times;</span>
                        <p>投稿方法の詳細</p>
                        <p>1. タイトル、本文、タグを入力します。未入力の欄があると投稿されません。</p>
                        <p>2. 「投稿する」ボタンをクリックします。すると投稿が完了しホーム画面に遷移します。</p>
                    </div>
                </div>
                <span class="popup-trigger" onclick="openPopup()">?</span>
                <button type="button" class="clear-button">削除する</button>
                <button type="submit" class="post-button">投稿する</button>
            </div>
        </form>
    </div>

    <script>
        const postButton = document.querySelector('.post-button');
        const clearButton = document.querySelector('.clear-button');
        const titleInput = document.querySelector('.title-input');
        const contentInput = document.querySelector('.content-input');
        const tagInput = document.querySelector('.tag-input');
        const errorMessage = document.querySelector('.error-message');

        postButton.addEventListener('click', (event) => {
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();
            const tag = tagInput.value.trim();

            if (title === '' || content === '' || tag === '') {
                event.preventDefault(); // フォームの送信をキャンセル
                showErrorMessage('タイトル・本文・タグを入力してください');
            } else {
                localStorage.setItem('postMessage', '投稿しました');
            }
        });

        clearButton.addEventListener('click', () => {
            titleInput.value = '';
            contentInput.value = '';
            tagInput.value = '';
            hideErrorMessage();
        });

        function showErrorMessage(message) {
            errorMessage.textContent = message;
            errorMessage.classList.add('show-message');
            setTimeout(() => {
                errorMessage.classList.remove('show-message');
            }, 3000);
        }

        function hideErrorMessage() {
            errorMessage.classList.remove('show-message');
        }

        // ローカルストレージから投稿メッセージを取得し、表示する
        window.onload = function() {
            const postMessage = localStorage.getItem('postMessage');
            if (postMessage) {
                showPostMessage(postMessage);
                localStorage.removeItem('postMessage'); // メッセージを削除する
            }
        }

        function showPostMessage(message) {
            const postMessageDiv = document.createElement('div');
            postMessageDiv.textContent = message;
            postMessageDiv.classList.add('post-message');
            document.body.appendChild(postMessageDiv);

            setTimeout(function() {
                postMessageDiv.style.opacity = '0'; // 3 秒後にメッセージを非表示にする
            }, 3000);
        }

        // ポップアップを表示する関数
        function openPopup() {
            document.getElementById("popup").style.display = "block";
        }

        // ポップアップを閉じる関数
        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }
    </script>

</body>

</html>