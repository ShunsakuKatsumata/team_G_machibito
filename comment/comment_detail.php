<!DOCTYPE html>
<html>
    <head> 
        <?php
        session_start(); ?>
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
                    <!-- <div style="text-align:right; padding-top:20px;">12回答</div> -->
                    <br>
                    <?php
                        $ident = $_GET['ident'];
                        // テーブルの中身を取り出す
                        require_once __DIR__.'/classes/question_post.php';
                        $question_post = new question_post();
                        $item = $question_post->get_question_ident($ident);
                        // 作成者の名前を取得
                        $author_name = $question_post->get_author_name($ident);
                    
                        echo '<h4 style="margin-top:0;">'.$item['title'].'</h4>';
                        echo '<table>';
                        echo '<tr class="user_icon_name">';
                            echo '<td>&nbsp;' . $author_name . 'さん</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td>'.$item['detail'].'</td>';
                        echo '</tr>';
                    echo '</table>';

                    // 編集ボタン
                    if ($_SESSION['user']['user_id'] == $item['user_id']) {
                        echo '<button class="post_edit_button" onclick="location.href=\'./new_post/new_question_post_edit.php?ident='.$ident.'\'">編集</button>';
                    }
                    // 削除ボタン
                    if ($_SESSION['user']['user_id'] == $item['user_id']) {
                        echo '<button class="post_delete_button" onclick="location.href=\'./post_comment/comment_post_delete.php?ident=' . $ident . '\'">削除</button>';
                    }
                    // 投稿者がボタンをクリックすると、その質問が解決済みに変更される
                    if ($_SESSION['user']['user_id'] == $item['user_id']) {
                        echo '<button class="is_resolved_update_button" onclick="location.href=\'./post_comment/is_resolved_update.php?ident=' . $ident . '\'">解決済み</button>';
                    }
                    ?>
                </div>

                <div class="menu_sort_answer_list">
                    <P><ul class="menu_sort">
                    <li style="float:left;"><img src="./../Image/icons8-sort.png"/></li>
                        <select name="pulldown_goodbutton" onchange="handleSortChange_answer(this.value)">
                            <option value="">ソート選択...</option> 
                            <option value="good-desc">いいねが多い順</option>
                            <!-- <option value="new-desc">回答が新しい順</option>
                            <option value="old-asc">回答が古い順</option> -->
                            <!-- <button onclick="location.href='./post_answer/good_count_sort.php?sort=\'old\''"> -->
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
                        // echo $items;
                        echo '<div class="detail_reply">';
                        echo '<h3>回答</h3>';
                        echo '<table>';
                            echo '<tr>';
                                // コメントのIDを取得
                                $comment_id = $item['ident'];
                                // コメントした人を取得
                                $answer_name = $answer_post->get_answer_name($comment_id );
                                echo '<td><div class="answer_name">&nbsp;' . $answer_name . 'さん</div></td>';
                                echo '</tr>';
                                // echo '<td>'.$item['ident'].'</td>';
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

                                // 回答テーブルのデータをjsonに変換し、JavaScriptに送る
                                $like_state_each_comment = json_encode($item['like_state']);
                                echo $like_state_each_comment;
                            echo '</tr>';
                            // 日付表示
                            echo '<tr>';
                                echo '<td class="post-date">'.$item['post_time'].'</td>';
                            echo '</tr>';
                            // 編集ボタン
                            if ($_SESSION['user']['user_id'] == $item['user_id']) {
                                echo '<tr>';
                                echo '<td><button class="post_edit_button" onclick="location.href=\'./edit_answer/edit_answer.php?post_id=' . $post_id . '&commentId=' . $item['ident'] . '\'">編集</button></td>';
                                echo '</tr>';
                            }
                            // 削除ボタン
                            if ($_SESSION['user']['user_id'] == $item['user_id']) {
                                echo '<tr>';
                                echo '<td><button class="post_delete_button" onclick="location.href=\'./post_answer/answer_post_delete.php?post_id=' . $post_id . '&commentId=' . $item['ident'] . '\'">削除</button></td>';
                                echo '</tr>';
                            }
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
                        echo '<p><textarea name="answer_post" rows="3" cols="45" placeholder="質問に回答する"></textarea></p>';
                        echo '<p><button>送信</button></p>';                      
                        echo '<input type="hidden" name="answer_ident" value="'.$ident.'">';
                        ?>
                    </div>
                </form>
            </div>
        <script>
            // 質問に対する回答をソート
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
            };

            window.addEventListener('DOMContentLoaded', () => {
                // 要素を取得
                const likeButton = document.querySelector('.answer_like_button');
                const likeIcon = document.querySelector('.answer_like_icon');
                
                // いいねボタンのクリックイベント
                likeButton.addEventListener('click', () => {
                    // like_stateを取得
                    var like_state = JSON.parse('<?php echo $like_state_each_comment; ?>');

                    // いいねの状態に応じてアイコンとカウントを更新
                    if (like_state) {
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
    </body>
</html>