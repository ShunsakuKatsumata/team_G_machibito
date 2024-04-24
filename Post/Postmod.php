<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿画面</title>
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <style>
        body {
            display: flex;
            background-color: #f5f5f5;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .post-container {
            max-width: 800px;
            width: 80%;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        .title-input {
            flex: 1;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .content-input {
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            width: 97%;
            height: 300px;
            padding: 10px;
            border-radius: 5px;
            resize: vertical;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .post-button,
        .clear-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .clear-button {
            background-color: #dc3545;
            color: #fff;
            margin-right: 10px;
        }

        .post-button {
            background-color: #007bff;
            color: #fff;
        }

        .error-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 1;
        }

        .show-message {
            opacity: 1;
        }

        .post-container {
        width: 80%; /* コンテナの幅を設定 */
        margin: 0 auto; /* 中央に配置 */
    }
    </style>
</head>

<body>
    <?php include '../sidebar/sidebar.php'; ?>
    <div class="main-content">
    <div class="error-message"></div>
    <div class="post-container">
        <div class="user-info">
            <div class="user-icon"></div>
            <input type="text" placeholder="タイトルを入力してください" class="title-input">
        </div>
        <textarea placeholder="本文を入力してください" class="content-input"></textarea>
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
        const errorMessage = document.querySelector('.error-message');

        postButton.addEventListener('click', () => {
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();

            if (title === '' || content === '') {
                showErrorMessage('タイトル・本文を入力してください');
            } else {
                // 投稿処理の実装...

                // 投稿一覧画面に遷移
                window.location.href = '../Display/Display.html';
            }
        });

        clearButton.addEventListener('click', () => {
            titleInput.value = '';
            contentInput.value = '';
            hideErrorMessage();
        });

        function showErrorMessage(message) {
            errorMessage.textContent = message;
            errorMessage.classList.add('show-message');
        }

        function hideErrorMessage() {
            errorMessage.classList.remove('show-message');
        }
    </script>
</body>

</html>