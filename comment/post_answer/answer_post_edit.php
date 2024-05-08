<?php
    // 変数を受け取る
    $answer_like_isliked = $_POST['answer_like_isliked'];
    $post_id = $_GET['postid'];

    // 回答の編集
    if ($answer_like_isliked){
        require_once __DIR__.'/../classes/answer_post.php';
        $answer_post = new answer_post();
    }

    header("Location: ./../comment_detail.php?ident=$post_id");
    exit;
?>