<?php
    // 変数を受け取る
    $speak_myknowledge = $_POST['answer_post'];
    $answer_ident = $_POST['answer_ident'];
    // $comment_detail_ident = $_GET['comment_detail_ident'];

    // 関数の実行
    require_once __DIR__.'/../classes/answer_post.php';
    $answer_post = new answer_post();
    $answer_post->answer_question($answer_ident, $speak_myknowledge);

    // require_once __DIR__.'/comment_detail.php?ident='.$answer_ident;
    header("Location: ./../comment_detail.php?ident=$answer_ident");
    exit;

    // comment_home画面に移動
    // header("Location: comment_detail.php");
    // exit;
?>

