
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

    // いいねの状態とカウントを管理する変数
    $isLiked = false;
    $count = 0;

    // データベースから現在のいいね数を取得
    $stmt = $pdo->prepare("SELECT nice FROM post WHERE post_id = :post_id");
    $stmt->bindParam(':post_id', $postId);
    $stmt->execute();
    $currentNice = $stmt->fetchColumn();

    // 初期のいいね数を設定
    $count = intval($currentNice);
    
    // いいねの状態を更新
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['liked'])) {
        $liked = intval($_POST['liked']);
        if ($liked === 1 && !$isLiked) {
            $count++; // いいねを増やす
            $isLiked = true;
        } else if ($liked === 0 && $isLiked) {
            $count--; // いいねを減らす
            $isLiked = false;
        }

        // データベースのいいね数を更新
        $stmt = $pdo->prepare("UPDATE post SET nice = :nice WHERE post_id = :post_id");
        $stmt->bindParam(':nice', $count, PDO::PARAM_INT);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();

        echo 'success'; // 更新成功をクライアントに返す
    }
}

} catch (PDOException $e) {
    echo "エラー：" . $e->getMessage();
}
?>

<!-- 以下はHTML部分 -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿詳細</title>
</head>

<body>
    <div class="post-detail">
        <div class="like-button" id="likeButton">
            <img class="like-icon" src="../Image/Good_white.png" alt="Like">
            <span class="like-count"><?php echo $count; ?></span>
        </div>
        <script>
            const likeButton = document.querySelector('.like-button');
            const likeIcon = likeButton.querySelector('.like-icon');
            const likeCount = likeButton.querySelector('.like-count');

            // クリックイベントの設定
            likeButton.addEventListener('click', () => {
                // いいねの状態をサーバーに送信
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 400) {
                        const response = xhr.responseText;
                        if (response === 'success') {
                            // 成功時の更新処理（クライアント側でカウントの更新などを行う）
                            const newCount = <?php echo $count; ?> + (<?php echo $isLiked ? 1 : -1; ?>);
                            likeCount.textContent = newCount;
                            likeIcon.src = <?php echo $isLiked ? "'../Image/Good_pink.png'" : "'../Image/Good_white.png'"; ?>;
                        } else {
                            console.error('データベースの更新中にエラーが発生しました');
                        }
                    } else {
                        console.error('データベースの更新中にエラーが発生しました');
                    }
                };

                const liked = <?php echo $isLiked ? '0' : '1'; ?>; // 現在の状態の反対を送信
                const postData = `liked=${liked}`;
                xhr.send(postData);
            });
        </script>

    </div>
</body>

</html>
