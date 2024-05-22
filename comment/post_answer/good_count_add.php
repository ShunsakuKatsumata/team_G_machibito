<!DOCTYPE html>
<html>
    <head> 
        <link rel="stylesheet" href="../comment.css">
        <link rel="stylesheet" href="sidebar/sidebar.css">
        <meta charset="UTF-8">

    </head>
    <body>
        <?php
            // 変数を受け取る
            $comment_id = $_GET['comment_id'];

            // 値を取得
            require_once __DIR__.'/../classes/answer_post.php';
            $answer_post = new answer_post();
            $item = $answer_post->get_answer_answerid($comment_id);

            if($item['like_state']){
                // テーブルをアップデート
                $like_state = 0;
                // $like_count = $item['like_count'] - 1;
                $like_count = $item['like_count'] + 1;
                $answer_post->edit_goodcount($comment_id, $like_count, $like_state);
            }else{
                // テーブルをアップデート
                $like_state = 1;
                $like_count = $item['like_count'] + 1;
                $answer_post->edit_goodcount($comment_id, $like_count, $like_state);
            }

            $comment_detail_id = $item['post_id'];
            header("Location: ./../comment_detail.php?ident=$comment_detail_id");
            exit;

        ?>
</body>