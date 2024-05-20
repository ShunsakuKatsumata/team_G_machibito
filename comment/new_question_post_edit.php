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

        // 質問の編集（テキスト）
        echo '<form method="POST" action="post_comment/comment_post_edit.php">';
        echo '<div class="user-info">';
        echo '<div class="user-icon"></div>';
        echo '<input name="qustion_title_edit_text" type="text" class="title-input" value="' . $title . '">';
        echo '</div>';
        echo '<textarea name="question_detail_edit_text" class="content-input">' . $detail . '</textarea>';
        // 編集ボタン
        echo '<div class="button-container">';
        echo '<button name="button1" class="post-button">編集する</button>';
        echo '</div>';
        echo '<input type="hidden" name="question_ident_edit_text" value="' . $item['ident'] . '">';
        echo '</form>';
        ?>
    </div>
</body>

</html>