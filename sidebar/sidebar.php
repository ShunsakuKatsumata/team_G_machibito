<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .sidebar-image {
            transition: transform 0.3s ease;
        }

        .sidebar-image:hover {
            transform: scale(0.9);
            /* ホバー時に拡大する */
        }
    </style>
</head>

<div class="sidebar-sidebar">
    <a href="../Display/Display.php">
        <img src="../Image/icon.png" alt="トップ画像" class="sidebar-image">
    </a>
    <nav>
        <ul>
            <li>
                <a href="<?php echo isset($_SESSION['user']['user_name']) ? '../mypage/mypage.php' : '../login/login.php'; ?>">
                    <img src="../Image/my.png" alt="ログイン">
                    <?php
                    // セッションが開始されていない場合にのみセッションを開始する
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    // ユーザー名がセッションに存在する場合はユーザー名を表示、存在しない場合は「ログイン」を表示
                    echo isset($_SESSION['user']['user_name']) ? $_SESSION['user']['user_name'] : 'ログイン';
                    ?>
                </a>
            </li>
            <li><a href="../Display/Display.php"><img src="../Image/hhome.png" alt="ホーム">ホーム</a></li>
            <!-- <li><a href=""><img src="../Image/my.png" alt="マイページ">マイページ</a></li> -->
            <li><a href="../Post/Post.php"><img src="../Image/post.png" alt="投稿する">投稿</a></li>
            <li><a href="../comment/comment_home.php"><img src="../Image/question.png" alt="質問">Q&A</a></li>
            <li><a href="../sidebar/logout.php"><img src="../Image/llogout.png" alt="ログアウト">ログアウト</a></li>
            <!-- <li><a href=""><img src="../Image/good.png" alt="いいね">いいね</a></li> -->
            <!-- <li><a href=""><img src="../Image/setting.png" alt="設定">設定</a></li> -->
        </ul>
    </nav>
</div>