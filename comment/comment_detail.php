<!DOCTYPE html>
<html>
    <head> 
        <link rel="stylesheet" href="comment.css">
        <link rel="stylesheet" href="../sidebar/sidebar.css">
        <meta charset="UTF-8">

        <script>
            window.addEventListener('DOMContentLoaded', () => {
                // 要素を取得
                const likeButton = document.querySelector('.answer_like_button');
                const likeIcon = document.querySelector('.answer_like_icon');

                // いいねの状態とカウントを管理する変数
                let isLiked = false;

                // いいねボタンのクリックイベント
                likeButton.addEventListener('click', () => {
                    
                    // いいねの状態を反転
                    isLiked = !isLiked;

                    // いいねの状態に応じてアイコンとカウントを更新
                    if (isLiked) {
                        console.log('a');
                        likeIcon.src = "./../Image/Good_pink.png";
                        likeButton.classList.add('liked');
                        // count++;
                    } else {
                        console.log('b');
                        likeIcon.src = "./../Image/Good_white.png";
                        likeButton.classList.remove('liked');
                        // count--;
                    }
                });
            });
        </script>
    </head>
    <body>
        <?php include '../sidebar/sidebar.php'; ?>
        
            <div class="detail1">
                <!-- 質問詳細画面 -->
                <!-- 投稿された質問 -->
                <div class="detail_q">
                    <h3 style="text-align:left; float:left;">質問</h3>
                    <div style="text-align:right; padding-top:20px;">12回答</div>
                    <br>
                    <?php
                        $ident = $_GET['ident'];
                        // テーブルの中身を取り出す
                        require_once __DIR__.'/classes/question_post.php';
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

                    // 編集ボタン
                    echo '<button class="post_edit_button" onclick="location.href=\'./new_post/new_question_post_edit.php?ident='.$ident.'\'">編集</button>';

                    // 削除ボタン
                    echo '<button class="post_delete_button" onclick="location.href=\'./post_comment/comment_post_delete.php?ident='.$ident.'\'">削除</button>';
                    ?>
                </div>
                
                <!-- コメント欄 -->
                <!-- 質問者の返信がある場合 -->
                <?php
                $post_id = $_GET['ident'];
                // テーブルの中身を取り出す
                require_once __DIR__.'/classes/answer_post.php';
                $answer_post = new answer_post();
                $items = $answer_post->get_answers($post_id);
                foreach($items as $item){
                    echo '<div class="detail_reply">';
                    echo '<h3>回答</h3>';
                    echo '<table>';
                        echo '<tr>';
                            echo '<td>'.$item['answer'].'</td>';
                        echo '</tr>';
                        // いいねボタン
                        echo '<tr>';
                            echo '<td class="answer_like_flex">';
                                $answer_like_isliked = false;
                                echo '<form method="POST" action="./post_answer/good_count_add.php">';
                                    echo '<div class="answer_like_button">';
                                        echo '<img class="answer_like_icon" src="./../Image/Good_white.png">';
                                        if ($answer_like_isliked){
                                            echo '<input type="hidden" name="answer_like_isliked" value='.$answer_like_bool.'>';
                                        }
                                    echo '</div>';
                                echo '</form>';
                                echo '<span class="answer_like_count">'.$item['like_count'].'</span>';
                            echo '</td>';
                        echo '</tr>';
                        // 編集ボタン
                        echo '<tr>';
                            echo '<td><button class="post_edit_button" onclick="location.href=\'./edit_answer/edit_answer.php?post_id='.$post_id.'&commentId='.$item['ident'].'\'">編集</button></td>';
                        echo '</tr>';
                        // 削除ボタン
                        echo '<tr>';
                            echo '<td><button class="post_delete_button" onclick="location.href=\'./post_answer/answer_post_delete.php?post_id='.$post_id.'&commentId='.$item['ident'].'\'">削除</button></td>';
                        echo '</tr>';
                    echo '</table>';
                    echo '</div>';
                }
                ?>
                <!-- 回答を記入する場所 -->
                <form method="POST" action="./post_answer/answer_post_add.php">
                    <div class="write_comment">
                        <?php
                        // <!-- <label>コメント記入</label> -->
                        $ident = $_GET['ident'];
                        echo '<p><textarea name="answer_post" rows="3" cols="45" placeholder="質問に回答する"></textarea></p>';
                        echo '<p><button>送信</button></p>';                      
                        echo '<input type="hidden" name="answer_ident" value="'.$ident.'">';
                        ?>
                    </div>
                </form>
            </div>
    </body>
</html>