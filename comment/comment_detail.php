<!DOCTYPE html>
<html lang="ja">
    <head> 
        <link rel="stylesheet" href="comment_detail.css">
        <link rel="stylesheet" href="../sidebar/sidebar.css">
        <meta charset="UTF-8">
        <title>各質問の投稿詳細画面</title>
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
                            echo '<td><img class="user_icon" src="./../Image/user-icon1.png" width="40px" height="40px"><div class="user_name">&nbsp;morimoriさん</div></td>';
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

                <div class="menu_sort_answer_list">
                    <P><ul class="menu_sort">
                        <li style="float:left;"><img src="./../Image/icons8-sort.png"/></li>
                        <select name="pulldown_goodbutton" onchange="handleSortChange_answer(this.value)">
                            <option value="">ソート選択...</option> 
                            <option value="good-desc">いいねが多い順</option>
                            <option value="new-desc">回答が新しい順</option>
                            <option value="old-asc">回答が古い順</option>
                        </select>
                    </ul></P>
                </div>
                
                <!-- コメント欄 -->
                <!-- 質問者の返信がある場合 -->
                <div class="post-list">
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
                                echo '<form method="POST" action="./post_answer/good_count_add.php">';
                                    echo '<td class="answer_like_flex">';
                                        echo '<div class="answer_like_button">';
                                            if ($item['like_state']){
                                                echo '<a href="./post_answer/good_count_add.php?comment_id='.$item['ident'].'"><img class="answer_like_icon" src="./../Image/Good_pink.png"></a>';
                                            }else{
                                                echo '<a href="./post_answer/good_count_add.php?comment_id='.$item['ident'].'"><img class="answer_like_icon" src="./../Image/Good_white.png"></a>';
                                            }
                                            echo '</div>';
                                        echo '<span class="answer_like_count">'.$item['like_count'].'</span>';
                                    echo '</td>';
                                echo '</form>';
                            echo '</tr>';
                            // 日付表示
                            echo '<tr>';
                                echo '<td class="post-date">'.$item['post_time'].'</td>';
                            echo '</tr>';
                            
                            echo '<tr>';
                                // 編集ボタン
                                echo '<td><button class="post_edit_button" onclick="location.href=\'./edit_answer/edit_answer.php?post_id='.$post_id.'&commentId='.$item['ident'].'\'">編集</button>';
                                // 削除ボタン
                                echo '<button class="post_delete_button" onclick="location.href=\'./post_answer/answer_post_delete.php?post_id='.$post_id.'&commentId='.$item['ident'].'\'">削除</button></td>';
                            echo '</tr>';
                            
                        echo '</table>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <!-- 回答を記入する場所 -->
                <form method="POST" action="./post_answer/answer_post_add.php">
                    <div class="write_comment">
                        <?php
                        // <!-- <label>コメント記入</label> -->
                        $ident = $_GET['ident'];
                        echo '<p><textarea class="answer_post_textarea" name="answer_post" placeholder="質問に回答する"></textarea></p>';
                        echo '<p><button>送信</button></p>';                      
                        echo '<input type="hidden" name="answer_ident" value="'.$ident.'">';
                        ?>
                    </div>
                </form>
            </div>
        <script>
            function handleSortChange_answer(value) {
                var postList = document.querySelector('.post-list');
                var postDetails = Array.from(postList.querySelectorAll('.detail_reply'));
                console.log('1');
                switch (value) {
                    case 'good-desc':
                        // 評価数降順
                        postDetails.sort(function(a, b) {
                            var dateA = new Date(a.querySelector('.answer_like_count').textContent.trim());
                            var dateB = new Date(b.querySelector('.answer_like_count').textContent.trim());
                            return dateB - dateA;
                        });
                        break;
                    case 'new-desc':
                        // 日付降順でソート
                        postDetails.sort(function(a, b) {
                            var dateA = new Date(a.querySelector('.post-date').textContent.trim());
                            var dateB = new Date(b.querySelector('.post-date').textContent.trim());
                            return dateB - dateA;
                        });
                        break;
                    case 'old-asc':
                        // 日付昇順でソート
                        postDetails.sort(function(a, b) {
                            var dateA = new Date(a.querySelector('.post-date').textContent.trim());
                            var dateB = new Date(b.querySelector('.post-date').textContent.trim());
                            return dateA - dateB;
                        });
                        break;
                    default:
                        return;
                }
                // ソートされた要素を再配置
                postDetails.forEach(function(postDetail) {
                    postList.appendChild(postDetail);
                });
            }
        </script>
    </body>
</html>