<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ログインしているかどうかを確認
    if (!isset($_SESSION['user'])) {
        header("Location: ../Login/Login.php");
        exit();
    }

    // ログインユーザーのユーザーIDを取得
    $user_id = $_SESSION['user']['user_id'];

    // フォームから送信された記事IDを取得
    $post_id = $_POST['post_id'];

    // データベースに接続
    $db_host = 'localhost';
    $db_user = 'kobe';
    $db_password = 'denshi';
    $db_name = 'post';
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // 接続をチェック
    if ($conn->connect_error) {
        die("データベース接続エラー: " . $conn->connect_error);
    }

    // ログインユーザーが記事の投稿者であることを確認
    $stmt = $conn->prepare("SELECT user_id FROM post WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($row['user_id'] === $user_id) {
            // 記事を削除
            $stmt = $conn->prepare("DELETE FROM post WHERE post_id = ?");
            $stmt->bind_param("i", $post_id);
            if ($stmt->execute()) {
                // 削除成功
                $_SESSION['deleteMessage'] = "投稿を削除しました";
            } else {
                $_SESSION['deleteMessage'] = "投稿の削除に失敗しました";
            }
        }
    }
}

// Display.php にリダイレクトする
header("Location: ../Display/Display.php");
exit();
