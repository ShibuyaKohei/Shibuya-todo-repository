<?php

trait DatabaseHandle
{
    //DB接続の際に用いるプロパティ
    protected $host = "localhost";
    protected $dbname = "PHP_test";
    protected $user = "root";
    protected $pass = "";

    //DBを格納するプロパティ
    protected $dbh;

    //サニタイズ後のpostのデータを格納するプロパティ（個々のクラスのコンストラクタで具体的な中身を格納してもらう）
    protected $sanitizedPost;

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

        return $this->dbh;
    }

    //CRUDの操作（$sqlを定義してから使用する） $sql内の文字列が「WHERE 1」のSELECT文の場合バインドの必要がないため$sanitizedPostが要らなくなるが、その場合は適宜空の配列を定義して引数に格納する
    protected function crudExecution(string $sql, array $sanitizedPost)
    {
        try {
            //SQL文の準備
            $stmt = $this->dbh->prepare($sql);
            //SQL文にバインドする箇所があったらその都度バインドする
            if (strpos($sql, ':id')) {
                $stmt->bindParam(":id", $sanitizedPost['id'], PDO::PARAM_INT);
            }
            if (strpos($sql, ':title')) {
                $stmt->bindParam(":title", $sanitizedPost['title'], PDO::PARAM_STR);
            }
            if (strpos($sql, ':content')) {
                $stmt->bindParam(":content", $sanitizedPost['content'], PDO::PARAM_STR);
            }
            //実行
            $stmt->execute();

            return $stmt;
        } catch(Exception $e) {
            exit($e->getMessage());
        }
    }
    //DB切断
    protected function pdoDisconnection()
    {
        $this->dbh = null;
        return $this->dbh;
    }
}
