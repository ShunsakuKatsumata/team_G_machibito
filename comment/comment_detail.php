<!DOCTYPE html>
<html>

<head>
    <?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: ../login/login.php');
        exit();
    } ?>
    <link rel="stylesheet" href="comment_detail.css">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <meta charset="UTF-8">
    <title>各質問の投稿詳細画面</title>
</head>

<body>
    <?php include '../sidebar/sidebar.php'; ?>
    

    <div class="detail">
        <!-- 質問詳細画面 -->
        <!-- 投稿された質問 -->
        <?php
        $ident = $_GET['ident'];
        // テーブルの中身を取り出す
        require_once __DIR__ . '/classes/question_post.php';
        $question_post = new question_post();
        $item = $question_post->get_question_ident($ident);
        // 作成者の名前を取得
        $author_name = $question_post->get_author_name($ident);

        echo '<table class="question-table">';
        echo '<tr class="user-icon-name">';
        echo '<td class="author-name">&nbsp;投稿者：' . $author_name . '</td>';
        echo '</tr>';
        echo '</table>';
        echo '<h4 class="question-title" style="margin-top:0;">' . $item['title'] . '</h4>';
        echo '<table class="question-content-table">';
        echo '<tr>';
        echo '<td>' . $item['detail'] . '</td>';
        echo '</tr>';
        echo '</table>';

        // 編集ボタン
        if ($_SESSION['user']['user_id'] == $item['user_id']) {
            echo '<button class="post-edit-button" onclick="location.href=\'./new_question_post_edit.php?ident=' . $ident . '\'">編集</button>';
        }
        // 削除ボタン
        if ($_SESSION['user']['user_id'] == $item['user_id']) {
            echo '<button class="post-delete-button" onclick="location.href=\'./post_comment/comment_post_delete.php?ident=' . $ident . '\'">削除</button>';
        }
        // 投稿者がボタンをクリックすると、その質問が解決済みに変更される
        if ($_SESSION['user']['user_id'] == $item['user_id']) {
            echo '<button class="is-resolved-update-button" onclick="location.href=\'./post_comment/is_resolved_update.php?ident=' . $ident . '\'">解決済み</button>';
        }
        ?>
    </div>

    <div class="reply">
        <div class="header-container">
            <div class="answer-header">◆回答◆</div>
            <div class="menu-sort-answer-list">
                <ul class="menu-sort">
                    <li style="float:left;"><img src="./../Image/icons8-sort.png" /></li>
                    <select name="pulldown_goodbutton" onchange="handleSortChange_answer(this.value)">
                        <option value="">ソート選択...</option>
                        <option value="good-desc">いいねが多い順</option>
                        <option value="new-desc">回答が新しい順</option>
                        <option value="old-asc">回答が古い順</option>
                    </select>
                </ul>
            </div>
        </div>

        <!-- コメント欄 -->
        <!-- 質問者の返信がある場合 -->
        <?php
        $post_id = $_GET['ident'];
        // テーブルの中身を取り出す
        require_once __DIR__ . '/classes/answer_post.php';
        $answer_post = new answer_post();
        $items = $answer_post->get_answers($post_id);
        echo '<div class="likeButton">';
        foreach ($items as $item) {
            echo '<div class="post-list">';
            echo '<div class="answer-block">';
            echo '<table class="answer-table">';
            echo '<tr class="answer-row">';
            // コメントのIDを取得
            $comment_id = $item['ident'];
            // コメントした人を取得
            $answer_name = $answer_post->get_answer_name($comment_id);
            echo '<td><div class="answer-name">&nbsp;回答者：' . $answer_name . '</div></td>';
            echo '</tr>';
            echo '<tr class="answer-row">';
            echo '<td class="answer-content">' . $item['answer'] . '</td>';
            echo '</tr>';
            // 日付表示
            echo '<tr class="answer-row">';
            echo '<td class="post-date">' . $item['post_time'] . '</td>';
            echo '</tr>';
            // いいねボタンとその数、編集・削除ボタン
            echo '<tr class="answer-row">';
            echo '<td class="answer-action-flex">';

            $dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
            $username = 'kobe';
            $password = 'denshi';

            try {
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($comment_id) {
                    $_SESSION['post_id'] = $_GET['ident'];
                    $_SESSION['comment_id'] = $comment_id; // セッションにcomment_idを保存
                }

                if (isset($_SESSION['comment_id'])) {
                    // commentIdは取得できている
                    $commentId = $_SESSION['comment_id'];
                    echo $commentId.' ';
                    $postId = $_SESSION['post_id'];

                    // post_idを使用してデータベースから該当の投稿を取得
                    $stmt = $pdo->prepare("SELECT * FROM question_answer WHERE ident = :ident");
                    $stmt->bindParam(':ident', $commentId);
                    $stmt->execute();
                    $postData = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$postData) {
                        // post_idに対応する投稿が見つからなかった場合のエラー処理　未記入
                    }
                } else {
                    // post_idがURLに含まれていない場合のエラー処理　未記入
                }


                // いいね数を取得
                $stmt = $pdo->prepare("SELECT like_count FROM question_answer WHERE ident = :ident");
                $stmt->bindParam(':ident', $commentId);
                $stmt->execute();
                $currentNice = $stmt->fetchColumn();

                // echo $item['like_count'].' ';

                // セッションに回答ごとのいいねの状態を保存（初期値はfalse）
                $isLikedKey = 'isLiked_' . $commentId;
                // echo $isLikedKey;

                // ここで変数を複数用意しなければならない
                // １個しかない

                // コメントの個数分ループ
                // foreach (){
                    $isLiked = isset($_SESSION[$isLikedKey]) ? $_SESSION[$isLikedKey] : false;
                    // [$isLiked_57, $isLiked_58, $isliked_59]
                // }

                //  Undefined array key "isLiked_58" in
                echo $_SESSION[$isLikedKey];
                // echo $isLiked;

                // いいねの処理
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['liked']) && $_POST['liked'] === 'toggle') {
                    $isLiked = !$isLiked;

                    // データベースのいいね数を増減
                    $count = intval($currentNice) + ($isLiked ? 1 : -1);

                    // データベースのいいね数を更新
                    $stmt = $pdo->prepare("UPDATE question_answer SET like_count = :like_count, like_state=:like_state WHERE ident = :comment_id");
                    $stmt->bindParam(':like_count', $count, PDO::PARAM_INT);
                    $stmt->bindParam(':like_state', $isLiked, PDO::PARAM_INT);
                    $stmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
                    $stmt->execute();

                    // セッションに回答ごとのいいねの状態を保存
                    $_SESSION[$isLikedKey] = $isLiked;
                    // 173テスト；
                    // unset($_SESSION[$isLikedKey]);
                    // 更新成功をクライアントに返す
                    echo json_encode(array('success' => true, 'count' => $count, 'isLiked' => $isLiked));
                    exit;
                }
            } catch (PDOException $e) {
                echo "エラー：" . $e->getMessage();
            }

            // いいね数を表示するデータベース
            $stmt = $pdo->prepare("SELECT like_count FROM question_answer WHERE ident = :ident");
            $stmt->bindParam(':ident', $commentId);
            $stmt->execute();
            $currentNice = $stmt->fetchColumn();

            // 初期のいいね数を設定
            $count = intval($currentNice);
            echo "<script type='text/javascript'>
                var count = " . json_encode($count) . ";
            </script>";

            echo '<div class="answer-like-button">';
            echo '<img class="answer-like-icon" src="'.($isLiked ? './../Image/Good_pink.png' : './../Image/Good_white.png') .'" alt="Like"></a>';
            echo '<span class="answer-like-count">'.$currentNice.'</span>';
            echo '</div>';

            

            echo '<div class="answer-edit-delete-container">';
            // 編集ボタン
            if ($_SESSION['user']['user_id'] == $item['user_id']) {
                echo '<button class="post-edit-button" onclick="location.href=\'./edit_answer/edit_answer.php?post_id=' . $post_id . '&commentId=' . $item['ident'] . '\'">編集</button>';
            }
            // 削除ボタン
            if ($_SESSION['user']['user_id'] == $item['user_id']) {
                echo '<button class="post-delete-button" onclick="location.href=\'./post_answer/answer_post_delete.php?post_id=' . $post_id . '&commentId=' . $item['ident'] . '\'">削除</button>';
            }
            echo '</div>';
            echo '</td>';
            echo '</tr>';
            echo '</table>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        ?>

        <!-- 回答を記入する場所 -->
        <form method="POST" action="./post_answer/answer_post_add.php">
            <div class="write_comment">
                <?php
                // <!-- <label>コメント記入</label> -->
                $ident = $_GET['ident'];
                echo '<textarea class="comment-textarea" name="answer_post" rows="3" cols="45" placeholder="質問に回答する"></textarea>';
                echo '<button class="comment-submit-button">送信</button>';
                echo '<input type="hidden" name="answer_ident" value="' . $ident . '">';
                ?>
            </div>
        </form>
    </div>


    <script>
        // 回答のいいねボタン
        document.addEventListener('DOMContentLoaded', () => {
            // const likeButton = document.querySelector('.likeButton');が間違い
            // const likeButton = document.querySelector('.likeButton');
            const likeButton = document.querySelector('.answer-like-button');
            const likeIcon = likeButton.querySelector('.answer-like-icon');
            const likeCount = likeButton.querySelector('.answer-like-count');
                

            // いいねボタンのクリックイベント
            likeButton.addEventListener('click', () => {
                const xhr = new XMLHttpRequest();

                // console.log($postId);
                
                // メソッド、通信先、
                xhr.open('POST', '<?php echo $_SERVER['PHP_SELF'] . '?ident='.$postId ?>', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                const postData = 'liked=toggle';
                xhr.send(postData);

                // リクエスト完了時の処理
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // ページの再読み込み
                            location.reload(); // ページを再読み込みして更新を反映
                            const response = JSON.parse(xhr.responseText);
                            updateLikeButton(response.isLiked, response.count);
                        } else {
                            // エラーが発生した場合の処理
                            console.error('いいねの処理中にエラーが発生しました');
                        }
                    }
                };
            });

            function updateLikeButton(isLiked, count) {
                const likeIcon = likeButton.querySelector('.answer-like-icon');
                const likeCount = likeButton.querySelector('.answer-like-count');

                likeIcon.src = isLiked ? '../Image/Good_pink.png' : '../Image/Good_white.png';
                likeCount.textContent = count;
            }
        });

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
        
    </script>
    
    <footer id="footer">
    <p id="page-top"><a href="#">Page Top</a></p> 
    </footer>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://coco-factory.jp/ugokuweb/wp-content/themes/ugokuweb/data/8-1-2/js/8-1-2.js"></script>
</body>

</html>