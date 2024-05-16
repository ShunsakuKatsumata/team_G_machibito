<!DOCTYPE html>
<html lang="ja">
    <head> 
        <?php
        session_start(); ?>
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
            <input class="menu-item_q" type="button" onclick="location.href='./new_post/new_question_post.php'" value="質問する">
            <!-- このソートなくてもいい気がする -->
            <!-- <P><ul class="menu_sort">
                <li style="float:left;"><img src="./../Image/icons8-sort.png"/></li>
                <select name="pulldown1">
                    <option>投稿が新しい順</option>
                    <option>投稿が古い順</option>
                    <option>回答が多い順</option>
                </select>
            </ul></P> -->
            <hr>
            <?php
                
            ?>
            <!-- 投稿された質問一覧（タイトルが表示されている） -->
            <table id="comment_home_item" align="center">
                <?php
                // onclick="location.href='comment_detail.html'">
                require_once __DIR__.'/classes/question_post.php';
                $question_post = new question_post();
                $questions_list = $question_post->get_questions_unsolved();
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
        <!-- JavaScript -->
        <div class="isresolved-message" id="postMessage_id"></div>
        <div class="isresolved-message" id="isresolved-message-id"></div>
    </body>
        <script>
            // 
            // ページ読み込み時に投稿メッセージがあれば表示する
            window.onload = function() {
                var postMessage = localStorage.getItem('postMessage');
                if (postMessage) {
                    var postMessageElement = document.getElementById('postMessage_id');
                    postMessageElement.innerText = postMessage;
                    postMessageElement.style.opacity = '1';
                    setTimeout(function() {
                        postMessageElement.style.opacity = '0';
                    }, 3000);
                    // メッセージを表示した後は削除する
                    localStorage.removeItem('postMessage');
                }
                
                // Delete_Post.php からのリダイレクトでセッションに保存されたメッセージがあれば表示する
                var isresolved_message = "<?php echo isset($_SESSION['isresolved-message']) ? $_SESSION['deleteMessage'] : '' ?>";
                if (isresolved_message) {
                    var isresolved_message_Element = document.getElementById('isresolved-message-id');
                    isresolved_message_Element.innerText = isresolved_message;
                    isresolved_message_Element.style.opacity = '1';
                    setTimeout(function() {
                        isresolved_message_Element.style.opacity = '0';
                    }, 3000);
                    // メッセージを表示した後は削除する
                    <?php unset($_SESSION['isresolved-message']); ?>
                }
            };
    </script>
</html>