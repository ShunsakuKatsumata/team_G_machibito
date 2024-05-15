<?php
    // 変数を受け取る
    $ident = $_GET['ident'];

    // 質問の編集
    require_once __DIR__.'/../classes/question_post.php';
    $question_post = new question_post();
    $question_post->edit_is_resolved($ident);

    // いいねを押した後の画面に遷移する
    // header("Location: ./../comment_home.php");
    // exit;
?>