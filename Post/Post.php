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
        <div class="user-info">
            <div class="user-icon"></div>
            <input type="text" placeholder="タイトルを入力してください" class="title-input">
        </div>
        <textarea placeholder="本文を入力してください" class="content-input"></textarea>
        <textarea placeholder="タグを入力して下さい。例）#Python #資格" class="tag-input"></textarea>
        <div class="button-container">
            <button class="clear-button">削除する</button>
            <button class="post-button">投稿する</button>
        </div>
    </div>

    <script>
        const postButton = document.querySelector('.post-button');
        const clearButton = document.querySelector('.clear-button');
        const titleInput = document.querySelector('.title-input');
        const contentInput = document.querySelector('.content-input');
        const tagInput = document.querySelector('.tag-input');
        const errorMessage = document.querySelector('.error-message');

        postButton.addEventListener('click', () => {
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();
            const tag = tagInput.value.trim();

            if (title === '' || content === '' || tag === '') {
                showErrorMessage('タイトル・本文・タグを入力してください');
            } else {
                // 投稿処理の実装...

                // 投稿一覧画面に遷移
                localStorage.setItem('postMessage', '投稿しました');
                window.location.href = '../Display/Display.php/';
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

        function showPostMessage(message) {
            var postMessage = document.createElement('div');
            postMessage.textContent = message;
            postMessage.classList.add('post-message');
            document.body.appendChild(postMessage);

            setTimeout(function() {
                postMessage.style.opacity = '0'; // 3 秒後にメッセージを非表示にする
            }, 3000);
        }
    </script>
</body>

</html>