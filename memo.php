<?php

//ストラテジーパターンの具体例

//Model
interface TaxCalculatorStrategy
{
    public function calculateTax($amount);
}

class NewYorkTaxCalculator implements TaxCalculatorStrategy
{
    public function calculateTax($amount)
    {
        return $amount * 0.08;
    }
}

class CaliforniaTaxCalculator implements TaxCalculatorStrategy
{
    public function calculateTax($amount)
    {
        return $amount * 0.075;
    }
}

class TaxCalculator
{
    private $strategy;

    public function __construct(TaxCalculatorStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function calculate($amount)
    {
        return $this->strategy->calculateTax($amount);
    }
}



// controller
$nyCalculator = new TaxCalculator(new NewYorkTaxCalculator());
$caCalculator = new TaxCalculator(new CaliforniaTaxCalculator());

echo $nyCalculator->calculate(100); // 8
echo $caCalculator->calculate(100); // 7.5

//新規作成
$sql="INSERT INTO posts VALUES (DEFAULT, :title, :content, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
$stmt=$dbh->prepare($sql);
$stmt->bindParam(":title", $sanitizedPost['title'], PDO::PARAM_STR);
$stmt->bindParam(":content", $sanitizedPost['contents'], PDO::PARAM_STR);
$stmt->execute();
$dbh=null;

//編集
$sql="UPDATE posts SET title=:title, content=:content, updated_at=CURRENT_TIMESTAMP WHERE ID=:ID";
$stmt=$dbh->prepare($sql);
$stmt->bindParam(":ID", $sanitizedPost['id'], PDO::PARAM_INT);
$stmt->bindParam(":title", $sanitizedPost['title'], PDO::PARAM_STR);
$stmt->bindParam(":content", $sanitizedPost['contents'], PDO::PARAM_STR);
$stmt->execute();
$dbh=null;

//削除
$sql="DELETE FROM posts WHERE ID=:ID";
$stmt=$dbh->prepare($sql);
$stmt->bindParam(":ID", $sanitizedPost['id'], PDO::PARAM_INT);
$stmt->execute();
$dbh=null;

//共通するのは
//$sql=
//$stmt=$dbh->prepare($sql);
//$stmt->bindParam
//$stmt->execute();

//新規　title content     if($id = null)
//編集　id title content  if()
//削除　id                if($id != null && $title = null)
