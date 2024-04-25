<?
require_once __DIR__ . '/comment_dbdata.php';

class Question_Post extends DbData{
    // 追加
    public function post_questions($title, $detail){
        $sql = "insert into question_post values(?, ?)";
        $this->exec($sql, [$title, $detail]);
    }
    // 取得
    public function get_questions($userId){
        // 現在利用しているユーザーを指定
        $sql = "select title, detail from question_post";
        $stmt = $this->query($sql, []);
        $items = $stmt->fetchAll();
        return $items;
    }
}
?>