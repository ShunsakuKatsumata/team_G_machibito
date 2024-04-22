<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>sample.php</title>
</head>

<body>
    <?php
    $dsn = 'mysql:host=localhost;dbname=post;charset=utf8';
    $user = 'kobe';
    $password = 'denshi';

    try {
        $pdo = new PDO($dsn, $user, $password);
        $sql = 'select * from post';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();

        foreach ($results as $result) {
            echo 'post_id:' . $result['post_id'] . ', タイトル：' . $result['title'] . ', 内容：' . $result['content'] . '<br>';
        }
    } catch (Exception $e) {
        echo 'Error:' . $e->getMessage();
        die();
    }
    $pdo = null;
    ?>
    <hr>
</body>

</html>