<!-- データベ～ス -->
<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
$username = 'kobe';
$password = 'denshi';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // URLパラメータからpost_idを取得
    if (isset($_GET['post_id'])) {
        $postId = $_GET['post_id'];
    } else {
        // post_idがURLに含まれていない場合はエラー処理などを行う
        die("エラー：投稿IDが指定されていません");
    }

    // post_idを使用してデータベースから該当の投稿を取得
    $stmt = $pdo->prepare("SELECT * FROM post WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();
    $postData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$postData) {
        die("エラー：該当する投稿が見つかりません");
    }

    // いいね数を取得
    $stmt = $pdo->prepare("SELECT nice FROM post WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();
    $currentNice = $stmt->fetchColumn();

    // セッションに投稿ごとのいいねの状態を保存（初期値はfalse）
    $isLikedKey = 'isLiked_' . $postId;
    $isLiked = isset($_SESSION[$isLikedKey]) ? $_SESSION[$isLikedKey] : false;

    // いいねの処理
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['liked']) && $_POST['liked'] === 'toggle') {
        $isLiked = !$isLiked;

        // データベースのいいね数を増減
        $count = intval($currentNice) + ($isLiked ? 1 : -1);

        // データベースのいいね数を更新
        $stmt = $pdo->prepare("UPDATE post SET nice = :nice WHERE post_id = :post_id");
        $stmt->bindParam(':nice', $count, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();

        // セッションに投稿ごとのいいねの状態を保存
        $_SESSION[$isLikedKey] = $isLiked;

        // 更新成功をクライアントに返す
        echo json_encode(array('success' => true, 'count' => $count, 'isLiked' => $isLiked));
        exit;
    }

    // リプライを投稿するデータベース処理
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $replyContent = $_POST['input-post'];
    
        // SQLクエリを準備
        $stmt = $pdo->prepare("INSERT INTO reply (post_id, reply) VALUES (:post_id, :reply)");
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':reply', $replyContent);
    
        // クエリを実行
        $stmt->execute();
    
        // 成功した場合、ページを再読み込み
        if ($postId !== null) {
            header("Location: ".$_SERVER['PHP_SELF']."?post_id=".$postId);
            exit();
        } else {
            // $postIdがnullの場合のエラーエラー処理　未記入
        }

        $stmt = $pdo->prepare('SELECT title, content FROM post WHERE post_id = ?');
        $stmt->execute([$postId]);
        $data = $stmt->fetch();

        echo 'Title: ' . $data['title'] . "\n";
        echo 'Content: ' . $data['content'] . "\n";
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
    <title><?php echo htmlspecialchars($titleData['title']); ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const likeButton = document.querySelector('.like-button');
            const likeIcon = likeButton.querySelector('.like-icon');
            const likeCount = likeButton.querySelector('.like-count');
            
            // いいねボタンのクリックイベント
            likeButton.addEventListener('click', () => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '<?php echo $_SERVER['PHP_SELF'] . '?post_id=' . $postId; ?>', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                const postData = 'liked=toggle';
                xhr.send(postData);

                // リクエスト完了時の処理
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // ページの再読み込み
                            location.reload(); // ページを再読み込みして更新を反映
                            const response = JSON.parse(xhr.responseText);
                            updateLikeButton(response.isLiked, response.count);
                        } else {
                            // エラーが発生した場合の処理
                            console.error('いいねの処理中にエラーが発生しました');
                        }
                    }
                };
            });
            function updateLikeButton(isLiked, count) {
                const likeIcon = likeButton.querySelector('.like-icon');
                const likeCount = likeButton.querySelector('.like-count');

                likeIcon.src = isLiked ? '../Image/Good_pink.png' : '../Image/Good_white.png';
                likeCount.textContent = count;
            }
        });
        
        window.addEventListener('DOMContentLoaded', () => {
            // 各要素を取得
            const replyButton = document.querySelector('.reply-button');
            const replyForm = document.querySelector('.reply-form');
            const replySubmitButton = document.querySelector('.reply-submit');
            const postMessage = document.querySelector('.post-message');
            const replyInput = document.querySelector('.reply-input');

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

            if (!replyListContent.classList.contains('show')) {
                replyListContent.style.maxHeight = '0';
            }

                replyListToggle.addEventListener('click', () => {
                    replyListContent.classList.toggle('show');

                    if (replyListContent.classList.contains('show')) {
                        replyListContent.style.maxHeight = replyListContent.scrollHeight + 'px';
                        toggleIcon.style.transform = 'rotate(180deg)';
                    } else {
                        replyListContent.style.maxHeight = '0';
                        toggleIcon.style.transform = 'rotate(0deg)';
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
        <div class="post-title"><?php echo $titleData['title'];?></div>
        <div class="post-content">
        <?php echo $contentData['content'];?>
        
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
            <?php foreach ($replyData as $reply): ?>
                <div class="reply-item">
                <div class="reply-user">閲覧者</div><!--< ?php echo $reply['user']; ?> -->
                    <div class="reply-content"><?php echo $reply['reply']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="post-actions">
            <div class="like-button" id="likeButton">
                <img class="like-icon" src="<?php echo $isLiked ? '../Image/Good_pink.png' : '../Image/Good_white.png'; ?>" alt="Like">
                <span class="like-count"><?php echo $currentNice; ?></span>
            </div>
            <div class="reply-button">
                <img class="reply-icon" src="../Image/SpeechBubble.png" alt="Reply">
                <span>リプライする</span>
            </div>
        </div>
        <div class="reply-form">
                <form method="POST" action="Post_Detail.php?post_id=<?php echo $postId; ?>">
                    <textarea name="input-post" class="reply-input" placeholder="リプライを入力してください"></textarea>
                    <button type="submit" name="submit-post" class="reply-submit">送信</button>
                </form>
            </div>
    <div class="post-message"></div>
</body>

</html>