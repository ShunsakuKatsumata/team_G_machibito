<?php
    // 変数を受け取る
    $ident = $_POST['question_ident_edit_text'];
    $title = $_POST['qustion_title_edit_text'];
    $detail = $_POST['question_detail_edit_text'];

    // 関数の実行
    require_once __DIR__.'/../classes/question_post.php';
    $question_post = new question_post();
    $question_post->edit_question($ident, $title, $detail);

    // echo $ident;

    // 
    header("Location: ./../comment_detail.php?ident=$ident");
    exit;1
?>