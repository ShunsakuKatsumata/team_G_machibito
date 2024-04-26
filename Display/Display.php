<!DOCTYPE html>
<html lang="ja">

<head>
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
        <!-- ここまで -->
    <div class="search-bar">
        <input type="text" class="search-input" placeholder="検索...">
        <div class="sort-buttons">
            <select class="sort-select" onchange="handleSortChange(this.value)">
                <option value="">ソート選択...</option>
                <option value="date-asc">日付 昇順</option>
                <option value="date-desc">日付 降順</option>
                <option value="likes-asc">評価 昇順</option>
                <option value="likes-desc">評価 降順</option>
            </select>
        </div>
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
        // データベースから投稿を取得
        $sql = "SELECT * FROM post";
        $result = $conn->query($sql);
        // 結果を表示
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $row['post_id'];
                echo '<div class="post-detail">';
                echo '<div class="post-date">' . $row["post_date"] . '</div>';
                echo '<div class="user-info">';
                echo '<div class="user-icon"></div>';
                echo '<span>' . $row["title"] . '</span>';
                echo '</div>';
                echo '<a href="../Post_Detail/Post_Detail.php?post_id=' . $row["post_id"] . '" class="post-title">' . $row["title"] . '</a>';
                echo '<div class="post-content">' . $row["content"] . '</div>';
                echo '<div class="like-button">';
                echo '<img class="like-icon" src="../Image/Good_white.png" alt="Like">';
                echo '<span class="like-count">0</span>'; // いいねの数は0に設定
                echo '<span class="post-tag">' . $row["tag"] . '</span>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "投稿がありません";
        }
        // データベース接続を閉じる
        $conn->close();
        ?>
    </div>
    <div class="post-message" id="postMessage"></div> <!-- 追加: 投稿メッセージの要素 -->
    <script>
        // ページ読み込み時に投稿メッセージがあれば表示する
        window.onload = function () {
            var postMessage = localStorage.getItem('postMessage');
            if (postMessage) {
                var postMessageElement = document.getElementById('postMessage');
                postMessageElement.innerText = postMessage;
                postMessageElement.style.opacity = '1';
                setTimeout(function () {
                    postMessageElement.style.opacity = '0';
                }, 3000);
                // メッセージを表示した後は削除する
                localStorage.removeItem('postMessage');
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

