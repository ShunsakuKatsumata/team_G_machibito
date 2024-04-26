<?php
    // 変数を受け取る
    $ident = $_POST['question_ident_edit'];

    // テーブルから投稿内容を取得
    require_once __DIR__.'/../classes/question_post.php';
    $question_post = new question_post();
    $item = $question_post->get_question_ident($ident);
    
    $title = $item['title'];
    $detail = $item['detail'];

    // 編集画面作成
    

    // 関数の実行
    $question_post->edit_question($ident, $title, $detail);

    // comment_home画面に移動
    // header("Location: comment_detail.php?ident=$answer_ident");
    // exit;

?>