<!DOCTYPE html>
<html lang="ja">
    <head> 
        <?php
        session_start();
        ?>
        <link rel="stylesheet" href="./edit_answer.css">
        <meta charset="UTF-8">
        <title>回答の編集画面</title>
    </head>
    <body>
            <div class="detail1">
                <!-- 質問 -->
                <div class="detail_q">
                    <h3 style="text-align:left; float:left;">質問</h3>
                    <div style="text-align:right; padding-top:20px;">12回答</div>
                    <br>
                    <?php
                        $ident = $_GET['post_id'];
                        // テーブルの中身を取り出す
                        require_once __DIR__.'/../classes/question_post.php';
                        $question_post = new question_post();
                        $item = $question_post->get_question_ident($ident);
                    
                        echo '<h4 style="margin-top:0;">'.$item['title'].'</h4>';
                        echo '<table>';
                        echo '<tr>';
                            echo '<td>'.$item['detail'].'</td>';
                        echo '</tr>';
                    echo '</table>';
                    ?>
                </div>
                
                <!-- コメント欄 -->
                <!-- 質問者の返信がある場合 -->
                <?php
                $comment_id = $_GET['commentId'];
                $post_id = $_GET['post_id'];
                // テーブルの中身を取り出す
                require_once __DIR__.'/../classes/answer_post.php';
                $answer_post = new answer_post();
                $item_answer = $answer_post->get_answer_answerid($comment_id);
                // $item_answer = $answer_post->get_answers(4);

        echo '<form method="POST" action="../post_answer/answer_post_edit.php?postid=' . $post_id . '">';
        echo '<div class="detail_reply">';
        echo '<h3>回答</h3>';
        echo '<table>';
        echo '<tr>';
        echo '<td><textarea name="answer_edit_text" class="title-input" rows="3" cols="45">' . $item_answer['answer'] . '</textarea></td>';
        echo '</tr>';
        echo '<input type="hidden" name="answer_ident_edit_text" value="' . $item_answer['ident'] . '">';

        // 投稿ボタン
        echo '<tr>';
        echo '<td><div class="button-container">';
        echo '<button name="button1" class="post-button">更新</button>';
        echo '</div></td>';
        echo '</tr>';
        // }
        echo '</table>';
        echo '</div>';
        echo '</form>';
        ?>
    </div>
</body>

</html>