<?php

//Todoテーブルの表示用に昇順、降順を区別してデータをfetchするストラテジーパターン
//トレイトDatabaseHandleの実装が必要

//具象クラス用のインターフェイス
interface FetchStrategy
{
    public function arrange();
}

//昇順の具象クラス
class TodoCreateAscend implements FetchStrategy
{
    use DatabaseHandle;

    //昇順のデータを$recに格納して与える
    public function arrange()
    {
        $this->pdoConnection();
        $sql='SELECT id,title,content,created_at,updated_at FROM todo WHERE 1 ORDER BY created_at ASC';
        $void = [];//$sqlにはバインドするための具体的なid, title, contentが存在しないので、sqlExecutionの第２引数部分に空の配列を格納。
        $stmt = $this->sqlExecution($sql, $void);
        $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->pdoDisconnection();
        return $rec;
    }
}

//降順の具象クラス
class TodoUpdateAscend implements FetchStrategy
{
    use DatabaseHandle;

    public function arrange()
    {
        $this->pdoConnection();
        $sql='SELECT id,title,content,created_at,updated_at FROM todo WHERE 1 ORDER BY updated_at ASC';
        $void = []; //$sqlにはバインドするための具体的なid, title, contentが存在しないので、sqlExecutionの第２引数部分に空の配列を格納。
        $stmt = $this->sqlExecution($sql, $void);
        $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->pdoDisconnection();
        return $rec;
    }
}

//コンテキストクラス
class TodoFetchArranger
{
    private $strategy;

    public function __construct(FetchStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function arrangement()
    {
        return $this->strategy->arrange();
    }
}
