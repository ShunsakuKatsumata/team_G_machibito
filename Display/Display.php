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
        <div class="post-detail">
            <div class="post-date">2024-04-15</div>
            <div class="user-info">
                <div class="user-icon"></div>
                <span>投稿者名1</span>
            </div>
            <a href="../Post_Detail/Post_Detail.html" class="post-title">投稿のタイトル1</a>
            <div class="post-content">
                投稿の本文1がここに表示されます。改行を含む本文の場合も、適切に表示されます。
            </div>
            <div class="like-button">
                <img class="like-icon" src="../Image/Good_white.png" alt="Like">
                <span class="like-count">97</span>
            </div>
        </div>
        <div class="post-detail">
            <div class="post-date">2024-04-14</div>
            <div class="user-info">
                <div class="user-icon"></div>
                <span>投稿者名2</span>
            </div>
            <a href="../Post_Detail/Post_Detail.html" class="post-title">投稿のタイトル2</a>
            <div class="post-content">
                投稿の本文2がここに表示されます。さまざまな内容が含まれることがあります。
            </div>
            <div class="like-button">
                <img class="like-icon" src="../Image/Good_white.png" alt="Like">
                <span class="like-count">120</span>
            </div>
        </div>
    </div>
    <div class="post-message" id="postMessage"></div> <!-- 追加: 投稿メッセージの要素 -->
    <script>
        function handleSortChange(value) {
            switch (value) {
                case 'date-asc':
                    console.log("Sort by date ascending");
                    break;
                case 'date-desc':
                    console.log("Sort by date descending");
                    break;
                case 'likes-asc':
                    console.log("Sort by likes ascending");
                    break;
                case 'likes-desc':
                    console.log("Sort by likes descending");
                    break;
            }
        }

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
    </script>
</body>

</html>