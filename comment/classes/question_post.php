<?php
require_once __DIR__ .'/comment_dbdata.php';

class question_post extends dbdata{
    // 質問を投稿
    public function post_questions($title, $detail, $user_id){
        $sql = "INSERT INTO question_post (title, detail, user_id, question_time) VALUES (?, ?, ?, NOW())";
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

    // 未解決の状態の質問のみ取得
    public function get_questions_unsolved(){
        // 現在利用しているユーザーを指定
        $sql = "select * from question_post where is_resolved=0";
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
        // 削除した投稿のID(post_id)に付いているコメントを削除する
        $sql2 = "delete from question_answer where post_id=?";
        $result = $this->exec($sql2, [$ident]);
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

    // 投稿者がボタンをクリックすると、その質問が解決済みの状態に変更される
    public function edit_is_resolved($ident){
        $sql = "update question_post set is_resolved=1 where ident=?";
        $result = $this->exec($sql, [$ident]);
    }

    // ソート機能付きの未解決の質問を取得
    public function get_questions_unsolved_sorted($sort) {
        switch ($sort) {
            case 'new':
                $sql = "SELECT * FROM question_post WHERE is_resolved=0 ORDER BY question_time DESC";
                break;
            case 'old':
                $sql = "SELECT * FROM question_post WHERE is_resolved=0 ORDER BY question_time ASC";
                break;
            case 'count':
                $sql = "SELECT * FROM question_post WHERE is_resolved=0 ORDER BY answer_count DESC";
                break;
            default:
                $sql = "SELECT * FROM question_post WHERE is_resolved=0 ORDER BY question_time DESC";
                break;
        }
        $stmt = $this->query($sql, []);
        $items = $stmt->fetchAll();
        return $items;
    }

}

?>