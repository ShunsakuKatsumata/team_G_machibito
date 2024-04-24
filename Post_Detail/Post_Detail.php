<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <title>投稿詳細</title>
    <style>
        @keyframes enlarge {
            0% {
                transform: scale(1.5);
            }

            100% {
                transform: scale(2);
            }
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .post-detail {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
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
            margin-bottom: 10px;
        }

        .post-content {
            line-height: 1.5;
            white-space: pre-wrap;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
        }

        .post-actions {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .like-button {
            display: flex;
            align-items: center;
            margin-right: 20px;
            cursor: pointer;
        }

        .like-icon {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }

        .reply-button {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .reply-icon {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }

        .reply-form {
            margin-top: 10px;
        }

        .reply-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
            border-radius: 5px;
            resize: vertical;
            box-sizing: border-box;
        }

        .liked .like-icon {
            content: url("../Image/Good_pink.png");
            animation: enlarge 0.5s ease;
        }

        .reply-form {
            margin-top: 10px;
            display: none;
        }

        .show-reply-form {
            display: block;
        }

        .reply-button {
            cursor: pointer;
        }

        .reply-submit {
            display: block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: auto;
            margin-right: auto;
            width: 8em
        }

        .reply-list {
            margin-top: 20px;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
            border-radius: 5px;
            padding: 10px;
        }

        .reply-list-header {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .reply-list-title {
            font-weight: bold;
        }

        .reply-list-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .show {
            max-height: 1000px;
        }

        .reply-list-toggle {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .reply-list-toggle span {
            margin-left: 5px;
        }

        .reply-list-toggle .toggle-icon {
            width: 16px;
            height: 16px;
            transition: transform 0.3s ease-in-out;
        }

        .reply-list-toggle.open .toggle-icon {
            transform: rotate(180deg);
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

        .error-message {
            background-color: #dc3545;
            /* エラーメッセージの文字色 */
        }
    </style>

    <script>
        
        window.addEventListener('DOMContentLoaded', () => {
            const likeButton = document.querySelector('.like-button');
            const likeIcon = document.querySelector('.like-icon');
            const likeCount = document.querySelector('.like-count');
            const replyButton = document.querySelector('.reply-button');
            const replyForm = document.querySelector('.reply-form');
            const replySubmitButton = document.querySelector('.reply-submit');
            const postMessage = document.querySelector('.post-message');
            const replyInput = document.querySelector('.reply-input');

            let isLiked = false;
            let count = 123;

            likeCount.textContent = count;

            likeButton.addEventListener('click', () => {
                isLiked = !isLiked;

                if (isLiked) {
                    likeIcon.src = "../Image/Good_pink.png";
                    likeButton.classList.add('liked');
                    count++;
                } else {
                    likeIcon.src = "../Image/Good_white.png";
                    likeButton.classList.remove('liked');
                    count--;
                }

                likeButton.querySelector('.like-count').textContent = count;
                if (likeButton.querySelector('.like-icon').src.includes('Good_pink.png')) {
                    likeButton.querySelector('.like-icon').style.animation = 'none';
                    void likeButton.offsetWidth;
                    likeButton.querySelector('.like-icon').style.animation = 'enlarge 0.5s ease';
                }
            });

            replyButton.addEventListener('click', () => {
                replyForm.classList.toggle('show-reply-form');
            });

            replySubmitButton.addEventListener('click', () => {
                // リプライ送信処理
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