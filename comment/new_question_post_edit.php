<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="new_question_post.css">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <title>質問の編集画面</title>

<body>
    <!-- サイドバー設定 -->
    <?php include '../sidebar/sidebar.php'; ?>
    <div class="error-message"></div>
    <div class="post-container">
        <?php
        // 変数を受け取る
        $ident = $_GET['ident'];

        // テーブルから投稿内容を取得
        require_once __DIR__ . '/./classes/question_post.php';
        $question_post = new question_post();
        $item = $question_post->get_question_ident($ident);

        $title = $item['title'];
        $detail = $item['detail'];
        // 関数の実行
        $question_post->edit_question($ident, $title, $detail);
        ?>
        <!-- 質問の編集（テキスト） -->
        <form method="POST" action="post_comment/comment_post_edit.php">
        <div class="user-info">
        <div class="user-icon"></div>
        <input id="title-input" name="qustion_title_edit_text" type="text" class="title-input" value="<?php echo $title; ?>">
        <div class="titleLength"><?php echo strval(mb_strlen($title)).'/35'?></div>
        
        </div>
        <textarea name="question_detail_edit_text" class="content-input"><?php echo $detail; ?></textarea>
        <!-- 編集ボタン -->
        <div class="button-container">
        <button name="button1" class="post-button">編集する</button>
        </div>
        <input type="hidden" name="question_ident_edit_text" value="<?php echo $item['ident'] ?>">
        </form>
    </div>
</body>
<script>
    // タイトルの文字数
    const titleInput = document.querySelector('.title-input');
    const titleLength = document.querySelector('.titleLength');
    const contentInput = document.querySelector('.content-input');
    const postButton = document.querySelector('.post-button');
    const errorMessage = document.querySelector('.error-message');

    const maxLength = 35;
    titleInput.addEventListener('input', () => {
        titleLength.textContent = titleInput.value.length;
        if(maxLength - titleInput.value.length < 0){
            titleLength.textContent = String(maxLength + Math.abs(maxLength - titleInput.value.length)) + '/35';
            titleLength.style.color = 'red'; // 最大文字数を超過したら赤字で表示する
        }else{
            titleLength.textContent = String(titleInput.value.length) + '/35';
            titleLength.style.color = '#444';
        }
    }, false);

    postButton.addEventListener('click', (event) => {
        const title = titleInput.value.trim();
        const content = contentInput.value.trim();

        // タイトルの文字数
        const title_length = titleInput.value.length

        if (title === '' || content === '') {
            event.preventDefault(); // フォームの送信をキャンセル
            showErrorMessage('タイトル・本文を入力してください');
        }
        else if(title_length>35){
            event.preventDefault(); // フォームの送信をキャンセル
            showErrorMessage('タイトルの文字数を35文字以内にしてください');
        } 
        else {
            localStorage.setItem('questionpostMessage', '投稿しました');
        }
    });

    function showErrorMessage(message) {
        errorMessage.textContent = message;
        errorMessage.classList.add('show-message');
        setTimeout(() => {
            errorMessage.classList.remove('show-message');
        }, 3000);
    }
</script>

</html>