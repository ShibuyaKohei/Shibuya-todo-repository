<?php

trait DbHandle
{
    //プロパティ
    protected $host = "localhost";
    protected $dbname = "PHP_test";
    protected $user = "root";
    protected $pass = "";

    //DBを格納する変数
    protected $dbh;

    //DB接続
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

    //DB切断
    protected function pdoDisconnection()
    {
        $dbh = null;
    }
}

//新規作成
//$sql="INSERT INTO posts VALUES (DEFAULT, :title, :content, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
//$stmt=$dbh->prepare($sql);
//$stmt->bindParam(":title", $sanitizedPost['title'], PDO::PARAM_STR);
//$stmt->bindParam(":content", $sanitizedPost['contents'], PDO::PARAM_STR);
//$stmt->execute();
//$dbh=null;

//編集
//$sql="UPDATE posts SET title=:title, content=:content, updated_at=CURRENT_TIMESTAMP WHERE ID=:ID";
//$stmt=$dbh->prepare($sql);
//$stmt->bindParam(":ID", $sanitizedPost['id'], PDO::PARAM_INT);
//$stmt->bindParam(":title", $sanitizedPost['title'], PDO::PARAM_STR);
//$stmt->bindParam(":content", $sanitizedPost['contents'], PDO::PARAM_STR);
// $stmt->execute();
// $dbh=null;

//削除
//$sql="DELETE FROM posts WHERE ID=:ID";
//$stmt=$dbh->prepare($sql);
//$stmt->bindParam(":ID", $sanitizedPost['id'], PDO::PARAM_INT);
//$stmt->execute();
// $dbh=null;

//共通するのは
//$sql=
//$stmt=$dbh->prepare($sql);
//$stmt->bindParam
//$stmt->execute();

//新規　title content     if($id === null && $title !=== null && content)
//読込　　　　　　　　　　　　if($id === null && title === null && content === null)
//編集　id title content  if()
//削除　id                if($id !=== null && $title === null)
//というかpostで比較すればいいんじゃないの
