<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ../../login/login.php');
        exit();
    } ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="new_question_post.css">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <title>質問の投稿画面</title>
</head>

<body>
    <!-- サイドバー設定 -->
    <?php include '../sidebar/sidebar.php'; ?>
    <div class="error-message"></div>
    <div class="post-container">
        <!-- 質問の編集（テキスト） -->
        <form method="POST" action="post_comment/comment_post_add.php">
            <div class="user-info">
                <div class="user-icon"></div>
                <input name="qustion_title" type="text" placeholder="タイトルを入力してください" class="title-input">
            </div>
            <textarea name="question_detail" placeholder="本文を入力してください" class="content-input"></textarea>
            <div class="button-container">
                <!-- 投稿方法の詳細ポップアップ -->
                <div id="popup" class="popup">
                    <div class="popup-content">
                        <span class="close-popup" onclick="closePopup()">&times;</span>
                        <p>投稿方法の詳細</p>
                        <p>1. タイトル、本文を入力します。未入力の欄があると投稿されません。</p>
                        <p>2. 投稿ボタンをクリックすると質問の投稿が完了し、質問一覧画面に遷移します。</p>
                        <p>※ 公序良俗を守り作成してください。</p>
                    </div>
                </div>
                <span class="popup-trigger" onclick="openPopup()">?</span>
                <button type="button" class="clear-button">削除する</button>
                <button type="submit" class="post-button" onclick="localStorage.removeItem('comment_blue'); localStorage.setItem('comment_blue', '投稿しました');">投稿する</button>
            </div>
        </form>


    </div>
</body>
<script>
    const postButton = document.querySelector('.post-button');
    const clearButton = document.querySelector('.clear-button');
    const titleInput = document.querySelector('.title-input');
    const contentInput = document.querySelector('.content-input');
    const errorMessage = document.querySelector('.error-message');

    postButton.addEventListener('click', (event) => {
        const title = titleInput.value.trim();
        const content = contentInput.value.trim();

        if (title === '' || content === '') {
            event.preventDefault(); // フォームの送信をキャンセル
            showErrorMessage('タイトル・本文を入力してください');
        } else {
            localStorage.setItem('questionpostMessage', '投稿しました');
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
        setTimeout(() => {
            errorMessage.classList.remove('show-message');
        }, 3000);
    }

    function hideErrorMessage() {
        errorMessage.classList.remove('show-message');
    }

    clearButton.addEventListener('click', () => {
        titleInput.value = '';
        contentInput.value = '';
        hideErrorMessage();
    });

    document.addEventListener('DOMContentLoaded', function() {
        // ポップアップを開くための要素を取得
        const popupTrigger = document.querySelector('.popup-trigger');
        // ポップアップの要素を取得
        const popup = document.getElementById('popup');

        // ポップアップを開くイベントリスナーを設定
        popupTrigger.addEventListener('click', function() {
            popup.style.display = 'block';
        });

        // ポップアップの×ボタンをクリックしたときの処理
        const closeButton = document.querySelector('.close-popup');
        closeButton.addEventListener('click', function() {
            popup.style.display = 'none';
        });

        // ポップアップ以外の部分がクリックされたときの処理
        window.addEventListener('click', function(event) {
            if (event.target === popup) {
                popup.style.display = 'none';
            }
        });
    });
</script>

</html>