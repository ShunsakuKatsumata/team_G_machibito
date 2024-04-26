<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <title>記事一覧</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }

        .search-bar {
            max-width: 800px;
            display: flex;
            justify-content: space-between;
            margin: 0 auto 20px;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            width: 70%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .sort-buttons {
            display: flex;
            align-items: center;
        }

        .sort-select {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .post-list {
            max-width: 800px;
            margin: 0 auto;
        }

        .post-detail {
            position: relative;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        .post-date {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 14px;
            color: #666;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .user-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ccc;
            margin-right: 10px;
        }

        .post-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }

        .post-content {
            line-height: 1.5;
            white-space: pre-wrap;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .like-icon {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }

        .post-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 1;
        }
        .post-tag{
            text-align: right;
            padding: 10px;
            margin-top:10px;
        }
    </style>
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
                echo '<div class="post-detail">';
                echo '<div class="post-date">' . $row["post_date"] . '</div>';
                echo '<div class="user-info">';
                echo '<div class="user-icon"></div>';
                echo '<span>' . $row["title"] . '</span>';
                echo '</div>';
                echo '<a href="../Post_Detail/Post_Detail.html" class="post-title">' . $row["title"] . '</a>';
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
            var postList = document.qudisplayerySelector('.post-list');
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

