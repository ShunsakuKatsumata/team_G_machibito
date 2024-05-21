<!DOCTYPE html>
<html lang="ja">

<head> 
    <?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ../login/login.php');
        exit();
    } ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <link rel="stylesheet" href="edit_answer.css">
    <title>回答の編集画面</title>
</head>

<body>
    <!-- サイドバー設定 -->
    <?php include '../sidebar/sidebar.php'; ?>
    <div class="detail1">
        <!-- 質問 -->
        <h3 style="text-align:left; float:left;">◆質問◆</h3>
        <br><br>      
        <?php
            $ident = $_GET['post_id'];
            // テーブルの中身を取り出す
            require_once __DIR__.'/../classes/question_post.php';
            $question_post = new question_post();
            $item = $question_post->get_question_ident($ident);
            echo '<h4 class="question-title" style="margin-top:0;">' . $item['title'] . '</h4>';
            echo '<table class="detail_q">';
            echo '<td>'.$item['detail'].'</td>';
        ?>
    </div>        

    <!-- コメント欄 -->
    <!-- 質問者の返信がある場合 -->
    <?php
        $comment_id = $_GET['commentId'];
        $post_id = $_GET['post_id'];
        // テーブルの中身を取り出す
        require_once __DIR__.'/../classes/answer_post.php';
        $answer_post = new answer_post();
        $item_answer = $answer_post->get_answer_answerid($comment_id);
        // $item_answer = $answer_post->get_answers(4);

        echo '<form method="POST" action="../post_answer/answer_post_edit.php?postid=' . $post_id . '">';
        echo '<div class="detail_reply">';
        echo '<table>';
        echo '<h3 class="answer-header">◆回答◆</h3>';
        echo '<textarea name="answer_edit_text" class="title_input">' . $item_answer['answer'] . '</textarea>';
        echo '<input type="hidden" name="answer_ident_edit_text" value="' . $item_answer['ident'] . '">';

        // 更新ボタン
        echo '<button name="button1" class="post-button">更新</button>';
    ?>

</body>

</html>