<?php
require_once __DIR__ .'/comment_dbdata.php';

class question_post extends dbdata{
    // 追加
    public function post_questions($title, $detail){
        $sql = "insert into question_post(title, detail) values(?, ?)";
        $result = $this->query($sql, [$title, $detail]);
    }

    // 取得
    public function get_questions(){
        // 現在利用しているユーザーを指定
        $sql = "select * from question_post";
        $stmt = $this->query($sql, []);
        $items = $stmt->fetchAll();
        return $items;
    }

    // 選択した投稿内容を取得する
    public function get_question_ident($ident){
        $sql = 'select * from question_post where ident=?';
        $stmt = $this->query($sql, [$ident]);
        $item = $stmt->fetch();
        return $item;
    }
    
}

?>