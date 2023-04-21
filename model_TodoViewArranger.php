<?php

//Todoテーブルの表示用に昇順、降順を区別してデータをfetchするストラテジーパターン

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
        $void = [];//$sqlにはバインドするための具体的なid, title, contentが存在しないので、crudExecutionの第２引数部分に空の配列を格納。
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
        $void = []; //$sqlにはバインドするための具体的なid, title, contentが存在しないので、crudExecutionの第２引数部分に空の配列を格納。
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
