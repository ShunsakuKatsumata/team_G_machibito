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
                echo '<form method="POST" action="../post_answer/answer_post_edit.php?postid='.$post_id.'">';
                    echo '<div class="detail_reply">';
                    echo '<h3>回答</h3>';
                    echo '<table>';
                        foreach($item_answer as $item){
                            echo '<tr>';
                                echo '<td><textarea name="answer_edit_text" class="title-input" rows="3" cols="45">'.$item['answer'].'</textarea></td>';
                            echo '</tr>';
                            echo '<input type="hidden" name="answer_ident_edit_text" value="'.$item['ident'].'">';
                            
                            // 投稿ボタン
                            echo '<tr>';
                                echo '<td><div class="button-container">';
                                    echo '<button name="button1" class="post-button">更新</button>';
                                echo '</div></td>';
                            echo '</tr>';
                        }
                    echo '</table>';
                    echo '</div>';
                    // 更新ボタン
                    
                    
                    // 変更予定 
                echo '</form>';
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