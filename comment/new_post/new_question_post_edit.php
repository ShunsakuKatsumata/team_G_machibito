<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>質問の編集画面</title>
    <style>
        body {
            display: flex;
            background-color: #f5f5f5;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .post-container {
            max-width: 800px;
            width: 80%;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .user-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ccc;
            margin-right: 10px;
        }

        .title-input {
            flex: 1;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .content-input {
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            width: 97%;
            height: 300px;
            padding: 10px;
            border-radius: 5px;
            resize: vertical;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .post-button,
        .clear-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .clear-button {
            background-color: #dc3545;
            color: #fff;
            margin-right: 10px;
        }

        .post-button {
            background-color: #007bff;
            color: #fff;
        }

        .error-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 1;
        }

        .show-message {
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="error-message"></div>
        <div class="post-container">
            <?php
                // 変数を受け取る
                $ident = $_GET['ident'];
            
                // テーブルから投稿内容を取得
                require_once __DIR__.'/./../classes/question_post.php';
                $question_post = new question_post();
                $item = $question_post->get_question_ident($ident);
                
                $title = $item['title'];
                $detail = $item['detail'];
                // 関数の実行
                $question_post->edit_question($ident, $title, $detail);

                // 質問の編集（テキスト）
                echo '<form method="POST" action="../post_comment/comment_post_edit.php">';
                    echo '<div class="user-info">';
                        echo '<div class="user-icon"></div>';
                        echo '<input name="qustion_title_edit_text" type="text" class="title-input" value="'.$title.'">';
                    echo '</div>';
                    echo '<textarea name="question_detail_edit_text" class="content-input">'.$detail.'</textarea>';
                    // 編集ボタン
                    echo '<div class="button-container">';
                        echo '<button name="button1" class="post-button">投稿する</button>';
                    echo '</div>';
                    echo '<input type="hidden" name="question_ident_edit_text" value="'.$item['ident'].'">';
                echo '</form>';
            ?>
    </div>
</body>

</html>


    <!-- // 変数を受け取る
    // $ident = $_POST['question_ident_edit'];

    // // テーブルから投稿内容を取得
    // require_once __DIR__.'/../classes/question_post.php';
    // $question_post = new question_post();
    // $item = $question_post->get_question_ident($ident);
    
    // $title = $item['title'];
    // $detail = $item['detail'];

    // // 編集画面作成
    

    // // 関数の実行
    // $question_post->edit_question($ident, $title, $detail);

    // // comment_home画面に移動
    // // header("Location: comment_detail.php?ident=$answer_ident");
    // // exit; -->

