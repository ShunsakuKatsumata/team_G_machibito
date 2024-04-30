<?php
require_once __DIR__ .'/comment_dbdata.php';

class answer_post extends dbdata{
    // 追加
    public function answer_question($post_id, $speak_myknowledge){
        $sql = "insert into question_answer(post_id, answer) values(?, ?)";
        $result = $this->query($sql, [$post_id, $speak_myknowledge]);
    }

    // 取得
    public function get_answers($post_id){
        $sql = "select * from question_answer where post_id = ?";
        $stmt = $this->query($sql, [$post_id]);
        $items = $stmt->fetchAll();
        return $items;
    }

    // post_idと回答IDを指定
    public function get_answers_answerid($ident, $post_id){
        $sql = 'select * from question_answer where post_id=? and ident=?';
        $stmt = $this->query($sql, [$ident, $post_id]);
        $item = $stmt->fetchAll();
        return $item;
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