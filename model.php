<?php

/*
クラス、トレイトの種類メモ
Sanitization:サニタイズを行うクラス。

DatabaseHandle:DBを操作するクラスが実装するトレイト。TodoDataManipulator, TodoAscendingStrategy, TodoDescendingStrategyに実装。

TodoDataManipulator:todoテーブルの操作を行うクラス。DatabaseHandleを実装。

ViewStrategy:昇順、降順表示用のインターフェイス。
TodoAscendingStrategy:具象クラス。昇順用。DatabaseHandleを実装。
TodoDescendingStrategy:具象クラス。降順用。DatabaseHandleを実装。
TodoViewArranger:コンテキストクラス。昇順または降順でデータをfetchする。
*/

//サニタイズを行うクラス
class Sanitization
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


//DBを操作するクラスが実装するトレイト(テーブルは任意)
trait DatabaseHandle
{
    //DB接続お際に用いるプロパティ
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
    //crudExecution($sql)

    //使用例2（SQL文の実行とfetch）
    //$sql='SELECT id,title,content,created_at,updated_at FROM PHP_test WHERE 1';
    //$stmt = crudExecution($sql)
    //$rec = $stmt->fetchAll(PDO::FETCH_ASSOC);



    //DB切断
    protected function pdoDisconnection()
    {
        $this->dbh = null;
        return $this->dbh;
    }
}



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
        //指定したSQL文に沿ってメソッドcrudExecution()を実行する
        $sql = "INSERT INTO todo VALUES (DEFAULT, :title, :content, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $this->crudExecution($sql, $this->sanitizedPost);
    }

    //編集前のテキストボックスに既存のデータをデフォルトで格納
    public function beforeUpdate()
    {
        //指定したSQL文に沿ってメソッドcrudExecution()を実行する
        $sql = $sql="SELECT id,title,content FROM posts WHERE id=:id";
        $stmt = $this->crudExecution($sql, $this->sanitizedPost);
        $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rec;
    }

    //編集
    public function update()
    {
        //もし「0<タイトル<31」かつ「0<内容」でなかったら、強制的に失敗ページに飛ばす処理
        if((0 < $this->sanitizedPost['title'] && $this->sanitizedPost['title'] < 31 && 0 < $this->sanitizedPost['content']) === false){
            header('view_edit_ng.html');
            exit();
        }else{
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


//以下211行目まで、Todoテーブルの表示機能。昇順と降順を区別するストラテジーパターンで構成。
interface ViewStrategy
{
    public function arrange();
}

//昇順のパターン
class TodoAscendingStrategy implements ViewStrategy
{
    use DatabaseHandle;

    //昇順のデータを$recに格納して与える
    public function arrange()
    {
        $this->pdoConnection();
        $sql='SELECT id,title,content,created_at,updated_at FROM todo WHERE 1 ORDER BY created_at ASC';
        $void = [];//SELECT文にはバインドするための具体的なid, title, contentが存在しないので、crudExecutionの第２引数部分に空の配列を格納。
        $stmt = $this->crudExecution($sql, $void);
        $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->pdoDisconnection();
        return $rec;
    }
}

//降順のパターン
class TodoDescendingStrategy implements ViewStrategy
{
    use DatabaseHandle;

    public function arrange()
    {
        $this->pdoConnection();
        $sql='SELECT id,title,content,created_at,updated_at FROM todo WHERE 1 ORDER BY created_at DESC';
        $void = []; //SELECT文にはバインドするための具体的なid, title, contentが存在しないので、crudExecutionの第２引数部分に空の配列を格納。
        $stmt = $this->crudExecution($sql, $void);
        $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->pdoDisconnection();
        return $rec;
    }
}

class TodoViewArranger
{
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
//$controller = new TodoDataManipulatior($sanitizedPost);
//$controller->detete();
//$controller->update();
//
//ViewArrangerの使用例
//$ascendingArrangement = new TodoViewArranger(new TodoAscendingStrategy());
//$data = $ascendingArrangement->arrangement();  view_top.phpで$dataをfor文で回す
