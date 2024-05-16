<?php
require_once __DIR__ . '/comment_dbdata.php';

class answer_post extends dbdata
{
    // 回答を投稿
    public function answer_question($post_id, $add_answer_post, $post_time, $user_id)
    {
        $sql = "insert into question_answer(post_id, answer, post_time, user_id) values(?, ?, ?, ?)";
        $result = $this->query($sql, [$post_id, $add_answer_post, $post_time, $user_id]);
    }

    // 回答を取得
    public function get_answers($post_id)
    {
        $sql = "select * from question_answer where post_id=?";
        $stmt = $this->query($sql, [$post_id]);
        $items = $stmt->fetchAll();
        return $items;
    }

    // 回答IDを指定して取得
    public function get_answer_answerid($ident)
    {
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
    public function edit_answer($ident, $answer)
    {
        $sql = "update question_answer set answer=? where ident=?";
        $result = $this->exec($sql, [$answer, $ident]);
    }

    // 投稿した回答を削除する
    public function delete_answer($ident)
    {
        $sql = "delete from question_answer where ident=?";
        $result = $this->exec($sql, [$ident]);
    }

    // 回答の投稿者の名前を取得
    public function get_answer_name($ident)
    {
        $sql = "SELECT account.user_name FROM question_answer 
            INNER JOIN account ON question_answer.user_id = account.user_id 
            WHERE ident = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$ident]);

        // 結果が空の場合に備えて、fetch() メソッドの結果をチェック
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result === false || $result === null) {
            // データが見つからない場合など、適切に処理を行う（例えばエラーをログに記録するなど）
            return null; // または空の文字列など、適切なデフォルト値を返す
        }

        return $result['user_name'];
    }


    // 
    public function edit_goodcount($ident, $like_count, $like_state)
    {
        $sql = "update question_answer set like_count=?, like_state=? where ident=?";
        $result = $this->exec($sql, [$like_count, $like_state, $ident]);
    }
}
