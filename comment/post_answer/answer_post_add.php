<?php
session_start();

// 変数を受け取る
$add_answer_post = $_POST['answer_post'];
$answer_ident = $_POST['answer_ident'];
$post_time = date("Y/m/d H:i:s");
$user_id =  $_SESSION['user']['user_id'];

// 回答の投稿
require_once __DIR__ . '/../classes/answer_post.php';
$answer_post = new answer_post();
$answer_post->answer_question($answer_ident, $add_answer_post, $post_time, $user_id);

// require_once __DIR__.'/comment_detail.php?ident='.$answer_ident;
header("Location: ./../comment_detail.php?ident=$answer_ident");
exit;
