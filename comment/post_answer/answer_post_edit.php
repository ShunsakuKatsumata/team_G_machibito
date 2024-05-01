<?php
    // 変数を受け取る
    $ident = $_POST['answer_ident_edit_text'];
    $answer = $_POST['answer_edit_text'];

    // 質問のIDを受け取る（画面遷移で使う）
    $post_id = $_GET['postid'];

    // 関数の実行
    require_once __DIR__.'/../classes/answer_post.php';
    $answer_post = new answer_post();
    $answer_post->edit_answer($ident, $answer);
    // 
    header("Location: ./../comment_detail.php?ident=$post_id");
    exit;
?>