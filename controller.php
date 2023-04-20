<?php

//view_create.phpから送られてくるpostは
//title, content, request(value = create)
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

//変数の定義（仕組み上必ず'create', 'edit', 'delete'になる。)
$request = $sanitizedPost['request'];

//インスタンスの生成
$controller = new DataManipulation($sanitizedPost);

//新規作成、編集、削除の実行
try {
    if ($request === 'create') {
        $controller->create();
    } elseif ($request === 'edit') {
        $controller->update();
    } elseif ($request === 'delete') {
        $controller->delete();
    }
} catch(Exception $e) {
    //$requestが改竄されていたらエラー
    exit($e->getMessage());
}
