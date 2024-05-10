<?php
    // 変数を受け取る
    // $title = $_POST['qustion_title'];
    $ident = $_GET['ident'];

    // 質問の削除
    require_once __DIR__.'/../classes/question_post.php';
    $question_post = new question_post();
    $question_post->delete_question($ident);

    // comment_home画面に移動
    header("Location: ./../comment_home.php");
    exit;
?>

