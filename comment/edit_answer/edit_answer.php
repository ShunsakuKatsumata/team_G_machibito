<!DOCTYPE html>
<html>
    <head> 
        <link rel="stylesheet" href="../comment.css">
        <meta charset="UTF-8">
    </head>
    <body>
        <!-- 質問 -->
            <div class="detail1">
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
                        echo '<tr class="user_icon_name">';
                            echo '<td><img class="user_icon" src="./image/user-icon1.png" width="40px" height="40px"><div class="user_name">&nbsp;morimoriさん</div></td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>'.$item['detail'].'</td>';
                        echo '</tr>';
                    echo '</table>';

                    // formを追加
                    // ?ident="'.$item['ident'].
                    // echo '<form method="POST" action="./new_question_post_edit.php">';
                    echo '<button class="post_edit_button" onclick="location.href=\'./new_post/new_question_post_edit.php?ident='.$ident.'\'">編集</button>';
                        // echo '<input name="question_ident_edit" type="hidden" value="'.$ident.'>';
                    //     echo '<input name="question_title_edit" type="hidden" value="'.$item['title'].'>';
                    //     echo '<input name="question_detail_edit" type="hidden" value="'.$item['detail'].'>';
                    // echo '</form>';

                    // formを追加
                    echo '<form method="POST" action="./post_comment/comment_post_delete.php">';
                        echo '<button class="post_delete_button">削除</button>';
                        echo '<input type="hidden" name="ident_delete" value='.$ident.'>';
                    echo '</form>';

                    // echo '<form method="POST" action="answer_post_add.php">';
                    //     echo '<input type="hidden" name="comment_detail_ident" value="'.$ident.'">';
                    // echo '</form>';
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
                $item_answer = $answer_post->get_answers_answerid($post_id, $comment_id);
                echo '<div class="detail_reply">';
                echo '<h3>回答</h3>';
                echo '<table>';
                    echo '<tr>';
                        echo '<td>'.$item_answer['answer'].'</td>';
                    echo '</tr>';
                echo '</table>';
                echo '</div>';
                ?>
            </div>
    </body>
</html>


<!-- <tr align="left">
                <td id="a"></td>
            </tr>
            <tr align="right">
                <td>返信</td>
            </tr> -->