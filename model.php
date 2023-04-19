<?php

//メモ
//DB接続用のトレイトとデータ操作用のインターフェイスを作成
//その下に個々のCRAD操作を確定していく
//トレイトとインターフェイスは固まっているので、その下にいくらでも拡張可能
//クラスごとに役割が整理されているので保守性もある
//このファイルをcontrollerにincludeする
//pdoConnection()でreturnを用いないことでエラーが起こらないか確認（前あったし）

trait DbHandle
{
    protected $host = "localhost";
    protected $dbname = "PHP_test";
    protected $user = "root";
    protected $pass = "";

    protected $dbh;

    protected function pdoConnection()
    {
        $dsn = "mysql:host=".$this->host.";dbname=".$this->dbname.";charset=utf8";
        try {
            // PDOのインスタンスをクラス変数に格納する
            $this->dbh = new PDO($dsn, $this->user, $this->pass);
        } catch(Exception $e) {
            // Exceptionが発生したら表示して終了
            exit($e->getMessage());
        }

        // DBのエラーを表示するモードを設定
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    protected function pdoDisconnection()
    {
        $dbh = null;
    }
}

class DataManupulation
{
    use DbHandle;

    public function __construct()
    {
        $this->pdoConnection();

        foreach ($_POST as $key=>$value) {
            $sanitizedPost[$key]=htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    }

    public function create($id, $title, $content)
    {
        $sql = "INSERT INTO posts VALUES (DEFAULT, :title, :content, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(":title", $sanitizedPost['title'], PDO::PARAM_STR);
        $stmt->bindParam(":content", $sanitizedPost['content'], PDO::PARAM_STR);
        $stmt->execute();
    }

    public function update($title, $content)
    {
        $sql = "UPDATE posts SET title=:title, content=:content, updated_at=CURRENT_TIMESTAMP WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(":ID", $sanitizedPost['id'], PDO::PARAM_INT);
        $stmt->bindParam(":title", $sanitizedPost['title'], PDO::PARAM_STR);
        $stmt->bindParam(":content", $sanitizedPost['content'], PDO::PARAM_STR);
        $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM posts WHERE id=:id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(":ID", $sanitizedPost['id'], PDO::PARAM_INT);
        $stmt->execute();
        $dbh=null;
    }
}


// 例
//$a = new Manupulator(new DataDeleteStrategy); 消去インスタンスの立ち上げ
//echo $a->manupulate($title, $content);　　　　　実行
//
