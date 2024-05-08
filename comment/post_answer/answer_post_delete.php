<?php
    // 変数を受け取る
    $comment_id = $_GET['commentId'];
    $post_id = $_GET['post_id'];

    // 回答の削除
    require_once __DIR__.'/../classes/answer_post.php';
    $answer_post = new answer_post();
    $answer_post->delete_answer($comment_id);

    // comment_home画面に移動
    header("Location: ./../comment_detail.php?ident=$post_id");
    exit;
?>