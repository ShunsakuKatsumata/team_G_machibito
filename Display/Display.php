<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    session_start(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <link rel="stylesheet" href="Display.css">
    <title>記事一覧</title>
</head>

<body>
    <!-- サイドバー -->
    <?php include '../sidebar/sidebar.php'; ?>
    <div class="main-content">
        <form method="GET" class="search-bar">
            <input type="text" name="search" class="search-input" placeholder="検索..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <div class="sort-buttons">
                <button type="submit" class="sort-select">検索</button>
            </div>
            <select class="sort-select" onchange="handleSortChange(this.value)">
                <option value="">ソート選択...</option>
                <option value="date-asc">日付 昇順</option>
                <option value="date-desc">日付 降順</option>
                <option value="likes-asc">評価 昇順</option>
                <option value="likes-desc">評価 降順</option>
            </select>
        </form>
    </div>
    <div class="post-list">
        <?php
        // データベースに接続
        $db_host = 'localhost';
        $db_user = 'kobe';
        $db_password = 'denshi';
        $db_name = 'post';
        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
        // 接続をチェック
        if ($conn->connect_error) {
            die("データベース接続エラー: " . $conn->connect_error);
        }
        // 検索キーワードがあれば、そのキーワードに基づいてSQLクエリを実行
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $sql = "SELECT post.*, account.user_name FROM post INNER JOIN account ON post.user_id = account.user_id WHERE post.title LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchTerm = '%' . $search . '%';
        $stmt->bind_param('s', $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['post_id'];
                echo '<div class="post-detail">';
                echo '<div class="post-date">' . $row["post_date"] . '</div>';
                echo '<div class="user-info">';
                echo '<div class="user-icon"></div>';
                echo '<span>投稿者: ' . $row["user_name"] . '</span>'; // 投稿者のユーザー名を表示
                echo '</div>';
                echo '<a href="../Post_Detail/Post_Detail.php?post_id=' . $row["post_id"] . '" class="post-title">' . $row["title"] . '</a>';
                echo '<div class="post-content">' . $row["content"] . '</div>';
                echo '<div class="like-button">';
                echo '<img class="like-icon" src="../Image/Good_white.png" alt="Like">';
                echo '<span class="like-count">' . $row["nice"] . '</span>';
                echo '<span class="post-tag">' . $row["tag"] . '</span>';

                // 削除ボタンを追加
                if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] == $row['user_id']) {
                    echo '<form method="POST" action="Delete_post.php">';
                    echo '<input type="hidden" name="post_id" value="' . $row['post_id'] . '">';
                    echo '<button type="submit" class="delete-button">削除</button>';
                    echo '</form>';
                }

                echo '</div>'; // like-button の終了タグ
                echo '</div>'; // post-detail の終了タグ
            }
        } else {
            echo "投稿がありません";
        }
        // データベース接続を閉じる
        $conn->close();
        ?>
    </div>
    <div class="post-message" id="postMessage"></div>
    <div class="post-message" id="deleteMessage"></div>
    <script>
        // ページ読み込み時に投稿メッセージがあれば表示する
        window.onload = function() {
            var postMessage = localStorage.getItem('postMessage');
            if (postMessage) {
                var postMessageElement = document.getElementById('postMessage');
                postMessageElement.innerText = postMessage;
                postMessageElement.style.opacity = '1';
                setTimeout(function() {
                    postMessageElement.style.opacity = '0';
                }, 3000);
                // メッセージを表示した後は削除する
                localStorage.removeItem('postMessage');
            }

            // Delete_Post.php からのリダイレクトでセッションに保存されたメッセージがあれば表示する
            var deleteMessage = "<?php echo isset($_SESSION['deleteMessage']) ? $_SESSION['deleteMessage'] : '' ?>";
            if (deleteMessage) {
                var deleteMessageElement = document.getElementById('deleteMessage');
                deleteMessageElement.innerText = deleteMessage;
                deleteMessageElement.style.opacity = '1';
                setTimeout(function() {
                    deleteMessageElement.style.opacity = '0';
                }, 3000);
                // メッセージを表示した後は削除する
                <?php unset($_SESSION['deleteMessage']); ?>
            }
        };

        function handleSortChange(value) {
            var postList = document.querySelector('.post-list');
            var postDetails = Array.from(postList.querySelectorAll('.post-detail'));

            switch (value) {
                case 'date-asc':
                    // 日付昇順でソート
                    postDetails.sort(function(a, b) {
                        var dateA = new Date(a.querySelector('.post-date').textContent.trim());
                        var dateB = new Date(b.querySelector('.post-date').textContent.trim());
                        return dateA - dateB;
                    });
                    break;
                case 'date-desc':
                    // 日付降順でソート
                    postDetails.sort(function(a, b) {
                        var dateA = new Date(a.querySelector('.post-date').textContent.trim());
                        var dateB = new Date(b.querySelector('.post-date').textContent.trim());
                        return dateB - dateA;
                    });
                    break;
                case 'likes-asc':
                    // 評価昇順でソート
                    postDetails.sort(function(a, b) {
                        var dateA = new Date(a.querySelector('.post-date').textContent.trim());
                        var dateB = new Date(b.querySelector('.post-date').textContent.trim());
                        return dateA - dateB;
                    });
                    break;
                case 'likes-desc':
                    // 評価降順でソート
                    postDetails.sort(function(a, b) {
                        var dateA = new Date(a.querySelector('.post-date').textContent.trim());
                        var dateB = new Date(b.querySelector('.post-date').textContent.trim());
                        return dateB - dateA;
                    });
                    break;
                default:
                    return;
            }
            // ソートされた要素を再配置
            postDetails.forEach(function(postDetail) {
                postList.appendChild(postDetail);
            });
        }
    </script>
</body>

</html>