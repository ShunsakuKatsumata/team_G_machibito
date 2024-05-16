<!DOCTYPE html>
<html lang="ja">
    <head> 
        <?php
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: ../login/login.php');
            exit();
        } ?>
        <title>コメントホーム</title>
        <link rel="stylesheet" href="comment_home.css">
        <link rel="stylesheet" href="../sidebar/sidebar.css">
        <meta charset="UTF-8">
        <title>質問の一覧画面</title>
    </head>
    <body>
        <!-- サイドバー設定 -->
        <?php include '../sidebar/sidebar.php'; ?>
        <div class="comment_home">
            <ul class="menu-home">
                <li class="menu-item_home">回答募集中&nbsp;|&nbsp;</li>
                <li class="menu-item_home">解決済み&nbsp;|&nbsp;</li>
                <li class="menu-item_home">カテゴリー</li>
                <li><input class="menu-item_q" type="button" onclick="location.href='./new_post/new_question_post.php'" value="質問する"></li>
            </ul>
            <P><ul class="menu_sort">
                <li style="float:left;"><img src="./../Image/icons8-sort.png"/></li>
                <select name="pulldown1">
                    <option>投稿が新しい順</option>
                    <option>投稿が古い順</option>
                    <option>回答が多い順</option>
                </select> -->
            </ul></P>
            <hr>
            <?php
                
            ?>
            <!-- 投稿された質問一覧（タイトルが表示されている） -->
            <table id="comment_home_item" align="center">
                <?php
                // onclick="location.href='comment_detail.html'">
                require_once __DIR__.'/classes/question_post.php';
                $question_post = new question_post();
                $questions_list = $question_post->get_questions();
                    foreach ($questions_list as $item) {
                        echo '<tr>';
                            echo '<td>';
                                // 投稿詳細画面に移動
                                echo '<button class="list_button" onclick="location.href=\'./comment_detail.php?ident='.$item['ident'].'\'">';       
                                    echo '<div><span class="post_item">'.$item['title'].'</span></div>';
                                echo '</button>';
                            echo '</td>';
                        echo '</tr>';
                    }
                ?>
            </table>
            
        </div>
    </body>
    <script>
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
</html>