<?php

include_once('model_DatabaseHandle.php');

//todoテーブルの新規作成、編集、削除を行うクラス
class TodoDataManipulator
{
    use DatabaseHandle;

    //DB接続と$sanitizedPostの格納
    public function __construct($sanitizedPost)
    {
        $this->pdoConnection();
        $this->sanitizedPost = $sanitizedPost;
    }

    //新規作成　$sanitizedPostの内容を使って、バリデーションを行う処理を行わなければならない。
    public function create()
    {
        //もし「0<タイトルの文字数<31」かつ「0<内容の文字数」でなかったら、強制的に失敗ページに飛ばす処理
        if ((0 < mb_strlen($this->sanitizedPost['title']) && mb_strlen($this->sanitizedPost['title']) < 31 && 0 < mb_strlen($this->sanitizedPost['content'])) === false) {
            header('Location:view_ng.html');
            exit();
        } else {
            //指定したSQL文に沿ってメソッドcrudExecution()を実行する
            $sql = "INSERT INTO todo VALUES (DEFAULT, :title, :content, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $this->crudExecution($sql, $this->sanitizedPost);
        }
    }

    //編集前のテキストボックスに既存のデータをデフォルトとして格納
    public function beforeUpdate()
    {
        //指定したSQL文に沿ってメソッドcrudExecution()を実行する
        $sql = $sql="SELECT id,title,content FROM todo WHERE id=:id";
        $stmt = $this->crudExecution($sql, $this->sanitizedPost);
        $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rec;
    }

    //編集
    public function update()
    {
        //もし「0<タイトルの文字数<31」かつ「0<内容の文字数」でなかったら、強制的に編集失敗ページに飛ばす処理
        if ((0 < mb_strlen($this->sanitizedPost['title']) && mb_strlen($this->sanitizedPost['title']) < 31 && 0 < mb_strlen($this->sanitizedPost['content'])) === false) {
            header('Location:view_ng.html');
            exit();
        } else {
            //指定したSQL文に沿ってメソッドcrudExecution()を実行する
            $sql = "UPDATE todo SET title=:title, content=:content, updated_at=CURRENT_TIMESTAMP WHERE id=:id";
            $this->crudExecution($sql, $this->sanitizedPost);
        }
    }

    //削除
    public function delete()
    {
        //指定したSQL文に沿ってメソッドcrudExecution()を実行する
        $sql = "DELETE FROM todo WHERE id=:id";
        $this->crudExecution($sql, $this->sanitizedPost);
    }

    //DB切断
    public function __destruct()
    {
        $this->pdoDisconnection();
    }
}
