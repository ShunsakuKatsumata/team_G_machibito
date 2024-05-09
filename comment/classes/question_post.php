<?php
require_once __DIR__ .'/comment_dbdata.php';

class question_post extends dbdata{
    // 質問を投稿
    public function post_questions($title, $detail){
        $sql = "insert into question_post(title, detail) values(?, ?)";
        $result = $this->exec($sql, [$title, $detail]);
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
}

?>