<?php

//view_create.phpから送られてくるpostは
//title, content, request(value = create) あと昇順と降順を保存するようなパラメータも欲しい
//
//
//
//
//
//
//
//
//
//
//

//modelからクラスを呼び込む
include_once('model.php');

//サニタイズ
$sanitizer = new sanitization($_POST);
$sanitizedPost = $sanitizer->sanitize();

//変数の定義（現状は仕組み上必ず'todoCreate', 'todoBeforeUpdate', 'todoUpdate', 'todoDelete'になる。適宜追加可能。)
$request = $sanitizedPost['request'];

//todoテーブルの新規作成、編集、削除の実行
try {
    if (in_array($request, ['todoCreate','todoUpdate','todoDelete'])) {
        //todoテーブル操作用のインスタンスの生成
        $todoController = new TodoDataManipulator($sanitizedPost);

        $ascendingArrangement = new TodoViewArranger(new TodoAscendingStrategy());//プロパティがない

        if ($request === 'todoCreate') {
            $todoController->create();
        } elseif ($request === 'todoUpdate') {
            $todoController->update();
        } elseif ($request === 'todoDelete') {
            $todoController->delete();
        }
        header('Location:view_top.php');//ちょっとここ保留。これでは表示まで正確にできない。
        exit();
    }
    if ($request === 'todoBeforeUpdate') {
        $default = $todoController->beforeUpdate();
        include('view_edit.php');
    }
} catch(Exception $e) {
    //$requestが改竄されていたらエラー
    exit($e->getMessage());
}
