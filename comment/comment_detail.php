<!DOCTYPE html>
<html>
    <head> 
        <link rel="stylesheet" href="comment.css">
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
                        $ident = $_GET['ident'];
                        // テーブルの中身を取り出す
                        require_once __DIR__.'/question_post.php';
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
                // テーブルの中身を取り出す
                require_once __DIR__.'/answer_post.php';
                $answer_post = new answer_post();
                $items = $answer_post->get_answers();
                foreach($items as $item){
                    echo '<div class="detail_reply">';
                    echo '<h3>回答</h3>';
                    echo '<table>';
                        echo '<tr>';
                            echo '<td>'.$item['answer'].'</td>';
                        echo '</tr>';
                    echo '</table>';
                    echo '</div>';
                }
                ?>
                <!-- コメント記入 -->
                <!-- <br> -->
                <form method="POST" action="answer_post_add.php">
                    <div class="write_comment">
                        <!-- <label>コメント記入</label> -->
                        <p><textarea name="answer_post" rows="3" cols="45" placeholder="質問に回答する"></textarea></p>
                        <p><button onclick="location.href='./comment_detail.php?ident='">送信</button></p>
                    </div>
                </form>
            </div>
    </body>
</html>


<!-- <tr align="left">
                <td id="a"></td>
            </tr>
            <tr align="right">
                <td>返信</td>
            </tr> -->