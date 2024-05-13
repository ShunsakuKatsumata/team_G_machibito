<?php
require_once __DIR__ .'/comment_dbdata.php';

class answer_post extends dbdata{
    // 回答を投稿
    public function answer_question($post_id, $add_answer_post, $post_time){
        $sql = "insert into question_answer(post_id, answer, post_time) values(?, ?, ?)";
        $result = $this->query($sql, [$post_id, $add_answer_post, $post_time]);
    }

    // 回答を取得
    public function get_answers($post_id){
        $sql = "select * from question_answer where post_id=?";
        $stmt = $this->query($sql, [$post_id]);
        $items = $stmt->fetchAll();
        return $items;
    }

    // 回答IDを指定して取得
    public function get_answer_answerid($ident){
        $sql = "select * from question_answer where ident=?";
        $stmt = $this->query($sql, [$ident]);
        $items = $stmt->fetch();
        return $items;
    }

    // // 選択した投稿内容を取得する
    // public function get_question_ident($ident){
    //     $sql = 'select * from question_post where ident=?';
    //     $stmt = $this->query($sql, [$ident]);
    //     $item = $stmt->fetch();
    //     return $item;
    // }

    // 投稿した回答を編集する
    public function edit_answer($ident, $answer){
        $sql = "update question_answer set answer=? where ident=?";
        $result = $this->exec($sql, [$answer, $ident]);
    }

    // 投稿した回答を削除する
    public function delete_answer($ident){
        $sql = "delete from question_answer where ident=?";
        $result = $this->exec($sql, [$ident]);
    } 

    // 
    public function edit_goodcount($ident, $like_count, $like_state){
        $sql = "update question_answer set like_count=?, like_state=? where ident=?";
        $result = $this->exec($sql, [$like_count, $like_state, $ident]);
    }
    
    
}

?>