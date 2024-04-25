<?php
require_once __DIR__ .'/comment_dbdata.php';

class answer_post extends dbdata{
    // 追加
    public function answer_question($speak_myknowledge){
        $sql = "insert into question_answer(answer) values(?)";
        $result = $this->query($sql, [$speak_myknowledge]);
    }

    // 取得
    public function get_answers(){
        // 現在利用しているユーザーを指定
        $sql = "select * from question_answer";
        $stmt = $this->query($sql, []);
        $items = $stmt->fetchAll();
        return $items;
    }

    // // 選択した投稿内容を取得する
    // public function get_question_ident($ident){
    //     $sql = 'select * from question_post where ident=?';
    //     $stmt = $this->query($sql, [$ident]);
    //     $item = $stmt->fetch();
    //     return $item;
    // }
    
}

?>