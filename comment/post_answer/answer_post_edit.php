<?php
    // 変数を受け取る
    $post_id = $_GET['postid'];
    // 関数で使う変数を受け取る
    $answer_edit_text = $_POST['answer_edit_text'];
    $answer_ident_edit_text = $_POST['answer_ident_edit_text'];

    // 回答の編集
    require_once __DIR__.'/../classes/answer_post.php';
    $answer_post = new answer_post();
    $answer_post->edit_answer($answer_ident_edit_text, $answer_edit_text);
    

    header("Location: ./../comment_detail.php?ident=$post_id");
    exit;
?>