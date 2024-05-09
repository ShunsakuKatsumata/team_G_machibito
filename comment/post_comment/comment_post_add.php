<?php
    // 変数を受け取る
    $title = $_POST['qustion_title'];
    $detail = $_POST['question_detail'];

    // 質問の投稿
    require_once __DIR__.'/../classes/question_post.php';
    $question_post = new question_post();
    $question_post->post_questions($title, $detail);

    // comment_home画面に移動
    header("Location: ./../comment_home.php");
    exit;
?>

