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
            let count = 123;

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
    <?php
        $servername = "localhost";
        $username = "kobe";
        $password = "denshi";

        // Create connection
        $conn = new mysqli($servername, $username, $password);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        echo "Connected successfully";
        ?>
    <div class="post-detail">
        <div class="user-info">
            <div class="user-icon"></div>
            <span>投稿者名</span>
        </div>
        <div class="post-title">投稿のタイトル</div>
        <div class="post-content">
            投稿の本文が表示されます。
            改行を含む本文の場合も、適切に表示されます。
        </div>
        <div class="reply-list">
            <div class="reply-list-header">
                <span class="reply-list-title">リプライ</span>
                <div class="reply-list-toggle">
                    <img class="toggle-icon" src="../Image/toggle2.png" alt="Toggle">
                </div>
            </div>
            <div class="reply-list-content" id="replyList">
                <div class="reply-item">
                    <div class="reply-user">質問者さん</div>
                    <div class="reply-content">これはリプライの例です。</div>
                </div>
                <div class="reply-item">
                    <div class="reply-user author">投稿者さん</div>
                    <div class="reply-content">これは投稿者からの回答の例です。</div>
                </div>
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
            <textarea class="reply-input" placeholder="リプライを入力してください"></textarea>
            <button class="reply-submit">送信</button>
        </div>
    </div>
    <div class="post-message"></div>
</body>

</html>