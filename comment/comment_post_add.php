<?php
    // 変数を受け取る
    $title = $_POST['qustion_title'];
    $detail = $_POST['question_detail'];

    // 関数の実行
    require_once __DIR__.'/question_post.php';
    $question_post = new Question_Post();
    $question_post->post_questions($title, $detail);
    

    require_once __DIR__.'/comment_home.php';
?>

