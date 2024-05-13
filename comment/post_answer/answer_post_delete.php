<?php
session_start();

// ログイン中のユーザーIDを取得
$user_id = $_SESSION['user']['user_id'];

// 変数を受け取る
$comment_id = $_GET['commentId'];
$post_id = $_GET['post_id'];

// 回答の所有者を取得
require_once __DIR__ . '/../classes/answer_post.php';
$answer_post = new answer_post();
$owner_id = $answer_post->getAnswerOwner($comment_id);

// ユーザーが回答の所有者かどうかを確認
if ($user_id == $owner_id) {
    // 回答の削除
    $answer_post->delete_answer($comment_id);
}

// comment_detail画面に移動
header("Location: ./../comment_detail.php?ident=$post_id");
exit;
