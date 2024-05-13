<?php
require_once __DIR__ .'/comment_dbdata.php';

class question_post extends dbdata{
    // 質問を投稿
    public function post_questions($title, $detail, $user_id){
        $sql = "insert into question_post(title, detail, user_id) values(?, ?, ?)";
        $result = $this->exec($sql, [$title, $detail, $user_id]);
    }

    // 質問を取得
    public function get_questions(){
        // 現在利用しているユーザーを指定
        $sql = "select * from question_post";
        $stmt = $this->query($sql, []);
        $items = $stmt->fetchAll();
        return $items;
    }

    // 選択した質問を取得する
    public function get_question_ident($ident){
        $sql = 'select * from question_post where ident=?';
        $stmt = $this->query($sql, [$ident]);
        $item = $stmt->fetch();
        return $item;
    }

    // 投稿した質問を編集する
    public function edit_question($ident, $title, $detail){
        $sql = "update question_post set title=?,detail=? where ident=?";
        $result = $this->exec($sql, [$title, $detail, $ident]);
    }

    // 投稿した質問を削除する
    public function delete_question($ident){
        $sql = "delete from question_post where ident=?";
        $result = $this->exec($sql, [$ident]);
    } 

    // 質問の投稿者の名前を取得
    public function get_author_name($ident){
        $sql = "SELECT account.user_name FROM question_post 
                INNER JOIN account ON question_post.user_id = account.user_id 
                WHERE ident = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ident]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['user_name'];
    }
}

?>