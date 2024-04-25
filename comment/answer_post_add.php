<?php
    // 変数を受け取る
    $speak_myknowledge = $_POST['answer_post'];
    // $detail = $_POST['question_detail'];

    // 関数の実行
    require_once __DIR__.'/answer_post.php';
    $answer_post = new answer_post();
    $answer_post->answer_question($speak_myknowledge);

    // comment_home画面に移動
    header("Location: comment_home.php");
    exit;
?>

