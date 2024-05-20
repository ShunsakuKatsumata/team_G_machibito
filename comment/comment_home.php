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
            <li class="menu_sort">
                <!-- ソート機能 -->
                <a href="?sort=new" class="sort-option">新しい順</a>｜
                <a href="?sort=old" class="sort-option">古い順</a>｜
                <a href="?sort=count" class="sort-option">多い順</a>
            </li>
            <li class="menu-item_home"><input class="menu-item_q" type="button" onclick="location.href='../mypage/mypage.php'" value="投稿した質問を見る"></li>
            <li class="menu-item_home"><input class="menu-item_q" type="button" onclick="location.href='./new_question_post.php'" value="質問する"></li>
        </ul>
    </div>

    <!-- 投稿された質問一覧（タイトルが表示されている） -->
    <div class="question-list-container">
        <div class="question-comment">◆回答待ちの質問一覧◆</div>
        <table id="comment_home_item">
            <!-- 投稿された質問一覧（タイトルが表示されている） -->
            <table id="comment_home_item" align="center">
                <?php
                // onclick="location.href='comment_detail.html'">
                require_once __DIR__.'/classes/question_post.php';
                $question_post = new question_post();
                $sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'new'; // 受け取ったソートをなげつけ
                $questions_list = $question_post->get_questions_unsolved_sorted($sort_order);   // ソートかつ未解決の質問を取得
                    foreach ($questions_list as $item) {
                        echo '<tr>';
                            echo '<td>';
                                // 投稿詳細画面に移動
                                echo '<button class="list_button" onclick="location.href=\'./comment_detail.php?ident='.$item['ident'].'\'">';       
                                    echo '<span class="post_item">'.$item['title'].'</span>';
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