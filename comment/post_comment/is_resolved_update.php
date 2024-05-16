<?php
    session_start();

    // 変数を受け取る
    $ident = $_GET['ident'];
    // ログインユーザーのユーザーIDを取得
    $user_id = $_SESSION['user']['user_id'];

    $db_host = 'localhost';
    $db_user = 'kobe';
    $db_password = 'denshi';
    $db_name = 'post';
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // 投稿者がボタンをクリックすると、その質問が解決済みの状態に変更される
    $stmt = $conn->prepare("update question_post set is_resolved=1 where ident=?");
    $stmt->bind_param("i", $ident);

    if ($stmt->execute()) {
        $_SESSION['isresolved-message'] = "投稿を解決済みに設定しました";
    } else {
        $_SESSION['isresolved-message'] = "投稿を解決済みの状態にすることに失敗しました";
    }

    // いいねを押した後の画面に遷移する
    header("Location: ./../comment_home.php");
    exit;
?>