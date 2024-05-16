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
                <a href="#" class="sort-option">新しい順</a>｜
                <a href="#" class="sort-option">古い順</a>｜
                <a href="#" class="sort-option">多い順</a>
            </li>
            <li class="menu-item_home"><input class="menu-item_q" type="button" onclick="location.href='../mypage/mypage.php'" value="投稿した質問を見る"></li>
            <li class="menu-item_home"><input class="menu-item_q" type="button" onclick="location.href='./new_post/new_question_post.php'" value="質問する"></li>
        </ul>
    </div>

    <!-- 投稿された質問一覧（タイトルが表示されている） -->
    <div class="question-list-container">
        <div class="question-comment">◆回答待ちの質問一覧◆</div>
        <table id="comment_home_item">
            <?php
            require_once __DIR__ . '/classes/question_post.php';
            $question_post = new question_post();
            $questions_list = $question_post->get_questions();
            foreach ($questions_list as $item) {
                echo '<tr>';
                echo '<td>';
                echo '<button class="list_button" onclick="location.href=\'./comment_detail.php?ident=' . $item['ident'] . '\'">';
                echo '<span class="post_item">' . $item['title'] . '</span>';
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