<?php

//機能は上へまとめていく

//サニタイズ
class sanitization
{
    private $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function sanitize()
    {
        foreach ($this->post as $key => $value) {
            $sanitizedPost[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            return $sanitizedPost;
        }
    }
}

//DBを操作するクラスが実装するトレイト
trait DbHandle
{
    //プロパティ
    protected $host = "localhost";
    protected $dbname = "PHP_test";
    protected $user = "root";
    protected $pass = "";

    //DBを格納する変数
    protected $dbh;

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

    //CRUDの操作（$sqlを定義してから使用する）
    protected function crud($sql, $sanitizedPost)
    {
        try {
            //SQL文のプリペア
            $stmt = $this->dbh->prepare($sql);
            //「SQL文にバインドする箇所があったらその都度バインドする」という方針
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
    //使用例1（SQL文の実行）
    //$sql = "INSERT INTO PHP_test VALUES (DEFAULT, :title, :content, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    //crud($sql)

    //使用例2（SQL文の実行とfetch）
    //$sql='SELECT id,title,content,created_at,updated_at FROM PHP_test WHERE 1';
    //$stmt = crud($sql)
    //$rec = $stmt->fetchAll(PDO::FETCH_ASSOC);



    //DB切断
    protected function pdoDisconnection()
    {
        $this->dbh = null;
        return $this->dbh;
    }
}



//データを操作するクラス(DB接続/切断とサニタイズをしつつ、新規作成/編集/削除)
class DataManipulation
{
    use DbHandle;

    //DB接続と$sanitizedPostの格納
    public function __construct($sanitizedPost)
    {
        $this->pdoConnection();
        $this->sanitizedPost = $sanitizedPost;
    }

    //新規作成
    public function create()
    {
        //指定したSQL文に沿ってメソッドcrud()が実行する
        $sql = "INSERT INTO PHP_test VALUES (DEFAULT, :title, :content, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $this->crud($sql, $this->sanitizedPost);
    }

    //編集
    public function update()
    {
        //指定したSQL文に沿ってメソッドcrud()が実行する
        $sql = "UPDATE PHP_test SET title=:title, content=:content, updated_at=CURRENT_TIMESTAMP WHERE id=:id";
        $this->crud($sql, $this->sanitizedPost);
    }

    //削除
    public function delete()
    {
        //指定したSQL文に沿ってメソッドcrud()が実行する
        $sql = "DELETE FROM PHP_test WHERE id=:id";
        $this->crud($sql, $this->sanitizedPost);
    }

    //DB切断
    public function __destruct()
    {
        $this->pdoDisconnection();
    }
}


//並び替え可能な表示機能
interface ViewStrategy
{
    public function arrange();
}

//昇順のパターン
class AscendingViewStrategy implements ViewStrategy
{
    use DbHandle;

    //$sanitizedPostの格納
    public function __construct($sanitizedPost)
    {
        $this->sanitizedPost = $sanitizedPost;
    }

    //昇順のデータを$recに格納して与える
    public function arrange()
    {
        $this->pdoConnection();
        $sql='SELECT id,title,content,created_at,updated_at FROM PHP_test WHERE 1 ORDER BY created_at ASC';
        $stmt = $this->crud($sql, $this->sanitizedPost);
        $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->pdoDisconnection();
        return $rec;
    }
}

class DescendingViewStrategy implements ViewStrategy
{
    use DbHandle;

    //$sanitizedPostの格納
    public function __construct($sanitizedPost)
    {
        $this->sanitizedPost = $sanitizedPost;
    }

    public function arrange()
    {
        $this->pdoConnection();
        $sql='SELECT id,title,content,created_at,updated_at FROM PHP_test WHERE 1 ORDER BY created_at DESC';
        $stmt = $this->crud($sql, $this->sanitizedPost);
        $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->pdoDisconnection();
        return $rec;
    }
}

class ViewArranger
{
    use DbHandle;

    private $strategy;

    public function __construct(ViewStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function arrangement()
    {
        return $this->strategy->arrange();
    }
}

//DataManipulationの使用例
//$controller = new DataManipulation($sanitizedPost);
//$controller->detete();
//$controller->update();
//
//ViewArrangerの使用例
//$ascendingArrangement = new ViewArranger(new AscendingStrategy);
//$data = $ascendingArrangement->arrangement();  view_top.phpで$dataをfor文で回す
