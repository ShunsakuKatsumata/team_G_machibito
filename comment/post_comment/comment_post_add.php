<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ../login/login.php');
        exit();
    }

    // 変数を受け取る
    $title = $_POST['qustion_title'];
    $detail = $_POST['question_detail'];
    $user_id =  $_SESSION['user']['user_id'];

    // 質問の投稿
    require_once __DIR__.'/../classes/question_post.php';
    $question_post = new question_post();
    $question_post->post_questions($title, $detail, $user_id);

    // comment_home画面に移動
    header("Location: ./../comment_home.php");
    exit;
?>

