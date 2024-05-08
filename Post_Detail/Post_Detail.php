<!-- データベ～ス -->
<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
$username = 'kobe';
$password = 'denshi';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['post_id'])) {
        $_SESSION['post_id'] = $_GET['post_id']; // セッションにpost_idを保存
    }

    if (isset($_SESSION['post_id'])) {
        $postId = $_SESSION['post_id'];

        // post_idを使用してデータベースから該当の投稿を取得
        $stmt = $pdo->prepare("SELECT * FROM post WHERE post_id = :post_id");
        $stmt->bindParam(':post_id', $postId);
        $stmt->execute();
        $postData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$postData) {
            // post_idに対応する投稿が見つからなかった場合のエラー処理　未記入
        }
    } else {
        // post_idがURLに含まれていない場合のエラー処理　未記入
    }

    // リプライを投稿するデータベース処理
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-post'])) {
        $replyContent = $_POST['input-post'];

        // SQLクエリを準備
        $stmt = $pdo->prepare("INSERT INTO reply (post_id, reply) VALUES (:post_id, :reply)");
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':reply', $replyContent);

        // クエリを実行
        $stmt->execute();

        // 成功した場合、ページを再読み込み
        header("Location: " . $_SERVER['PHP_SELF'] . "?post_id=" . $postId);
        exit();
    }

    // リプライを表示するデータベース処理
    $stmt = $pdo->prepare("SELECT * FROM reply WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();
    $replyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // タイトルを表示するデータベース
    $stmt = $pdo->prepare("SELECT title FROM post WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();
    $titleData = $stmt->fetch(PDO::FETCH_ASSOC);

    // 本文を表示するデータベース
    $stmt = $pdo->prepare("SELECT content FROM post WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();
    $contentData = $stmt->fetch(PDO::FETCH_ASSOC);

    // いいね数を表示するデータベース
    $stmt = $pdo->prepare("SELECT nice FROM post WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();
    $currentNice = $stmt->fetchColumn();

    // 初期のいいね数を設定
    $count = intval($currentNice);
    echo "<script type='text/javascript'>var count = " . json_encode($count) . ";</script>";
} catch (PDOException $e) {
    echo "エラー：" . $e->getMessage();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <link rel="stylesheet" href="post_detail.css">
    <title>投稿詳細</title>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // 各要素を取得
            const likeButton = document.querySelector('.like-button');
            const likeIcon = document.querySelector('.like-icon');
            const likeCount = document.querySelector('.like-count');
            const replyButton = document.querySelector('.reply-button');
            const replyForm = document.querySelector('.reply-form');
            const replySubmitButton = document.querySelector('.reply-submit');
            const postMessage = document.querySelector('.post-message');
            const replyInput = document.querySelector('.reply-input');

            // いいねの状態とカウントを管理する変数
            let isLiked = false;
            // count =123; 過去の初期値

            window.onload = function() {
                document.getElementById('likeCount').textContent = count;
            };

            // 初期のいいねの数を表示
            likeCount.textContent = count;

            // いいねボタンのクリックイベント
            likeButton.addEventListener('click', () => {
                // いいねの状態を反転
                isLiked = !isLiked;

                // いいねの状態に応じてアイコンとカウントを更新
                if (isLiked) {
                    likeIcon.src = "../Image/Good_pink.png";
                    likeButton.classList.add('liked');
                    count++;
                } else {
                    likeIcon.src = "../Image/Good_white.png";
                    likeButton.classList.remove('liked');
                    count--;
                }

                // 更新したカウントを表示
                likeButton.querySelector('.like-count').textContent = count;
                // アニメーションを再生
                if (likeButton.querySelector('.like-icon').src.includes('Good_pink.png')) {
                    likeButton.querySelector('.like-icon').style.animation = 'none';
                    void likeButton.offsetWidth;
                    likeButton.querySelector('.like-icon').style.animation = 'enlarge 0.5s ease';
                }
            });

            // リプライボタンのクリックイベント
            replyButton.addEventListener('click', () => {
                // リプライフォームの表示・非表示を切り替え
                replyForm.classList.toggle('show-reply-form');
            });

            // リプライ送信ボタンのクリックイベント
            replySubmitButton.addEventListener('click', () => {
                // リプライ送信処理 空かチェック
                if (replyInput.value.trim() === '') {
                    // 内容が空の場合はエラーメッセージを表示
                    showErrorMessage('内容を入力してください');
                } else {
                    // 内容が入力されている場合は投稿しましたメッセージを表示
                    showPostMessage();
                }
            });

            // 投稿しましたメッセージを表示する関数
            function showPostMessage() {
                postMessage.textContent = '投稿しました';
                postMessage.style.opacity = '1';
                setTimeout(() => {
                    postMessage.style.opacity = '0';
                }, 3000);
            }

            // エラーメッセージを表示する関数
            function showErrorMessage(message) {
                postMessage.textContent = message;
                postMessage.classList.add('error-message'); // エラーメッセージにクラスを追加
                postMessage.style.opacity = '1';
                setTimeout(() => {
                    postMessage.style.opacity = '0';
                    postMessage.classList.remove('error-message'); // エラーメッセージのクラスを削除
                }, 3000);
            }
        });

        // リプライリストの表示・非表示を切り替えるイベントリスナー
        window.addEventListener('DOMContentLoaded', () => {
            const replyListToggle = document.querySelector('.reply-list-toggle');
            const replyListContent = document.querySelector('.reply-list-content');
            const toggleIcon = replyListToggle.querySelector('.toggle-icon');

            // 初期状態でリプライリストを開いた状態にする
            replyListContent.classList.add('show');
            toggleIcon.style.transform = 'rotate(180deg)'; // 初期状態で▽の向きにする

            replyListToggle.addEventListener('click', () => {
                replyListContent.classList.toggle('show');

                if (replyListContent.classList.contains('show')) {
                    replyListContent.style.maxHeight = replyListContent.scrollHeight + 'px';
                    toggleIcon.style.transform = 'rotate(180deg)';
                } else {
                    replyListContent.style.maxHeight = '0';
                    toggleIcon.style.transform = 'rotate(0deg)'; // △に変更
                }
            });
        });
    </script>
</head>

<body>
    <!-- サイドバー -->
    <?php include '../sidebar/sidebar.php'; ?>
    <div class="main-content">
        <!-- ここまで -->
        <div class="post-detail">
            <div class="user-info">
                <div class="user-icon"></div>
                <span>投稿者名</span>
            </div>
            <div class="post-title"><?php echo $titleData['title']; ?></div>
            <div class="post-content">
                <?php echo $contentData['content']; ?>
                <!-- ここだけはhtmlで出力したいかも -->
            </div>
            <div class="reply-list">
                <div class="reply-list-header">
                    <span class="reply-list-title">リプライ</span>
                    <div class="reply-list-toggle">
                        <img class="toggle-icon" src="../Image/toggle2.png" alt="Toggle">
                    </div>
                </div>
                <!-- ユーザー管理が追加されてから追加する処理() -->
                <div class="reply-list-content" id="replyList">
                    <div class="reply-item">
                        <div class="reply-user"></div>
                        <div class="reply-content"></div>
                    </div>
                    <!-- ループ処理でデータを表示-->
                    <?php foreach ($replyData as $reply) : ?>
                        <div class="reply-item">
                            <div class="reply-user">閲覧者</div><!--< ?php echo $reply['user']; ?> -->
                            <div class="reply-content"><?php echo $reply['reply']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="post-actions">
                <div class="like-button" id="likeButton">
                    <img class="like-icon" src="../Image/Good_white.png" alt="Like">
                    <span class="like-count">0</span>
                </div>
                <div class="reply-button">
                    <img class="reply-icon" src="../Image/SpeechBubble.png" alt="Reply">
                    <span>リプライする</span>
                </div>
            </div>
            <div class="reply-form">
                <form method="POST" action="Post_Detail.php">
                    <textarea name="input-post" class="reply-input" placeholder="リプライを入力してください"></textarea>
                    <button type="submit" name="submit-post" class="reply-submit">送信</button>
                </form>
            </div>
        </div>
        <div class="post-message"></div>
</body>

</html>