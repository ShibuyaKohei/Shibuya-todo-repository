<?php

/*POST一覧
id(データのID)
title(データのタイトル)
content(データの内容)
request('todoCreate', 'todoBeforeUpdate', 'todoUpdate', 'todoDelete', 'todoAscend', 'todoDescend')
*/

//コントローラとビューは$_SESSIONでデータのやり取りをする
session_start();
session_regenerate_id(true);

//modelからクラスを呼び込む
include_once('model.php');

//サニタイズ
$sanitizer = new Sanitization($_POST);
$sanitizedPost = $sanitizer->sanitize();

//変数の定義（仕組み上必ず'todoCreate', 'todoBeforeUpdate', 'todoUpdate', 'todoDelete', 'todoAscend', 'todoDescend'のどれかになる。)
$request = $sanitizedPost['request'];


try {
    //todoテーブルの新規作成、編集、削除を行う際の処理
    if (in_array($request, ['todoCreate','todoUpdate','todoDelete'])) {
        $todoController = new TodoDataManipulator($sanitizedPost);//todoテーブル操作用のインスタンスの生成

        //データベースの操作
        if ($request === 'todoCreate') {
            $todoController->create();
        } elseif ($request === 'todoUpdate') {
            $todoController->update();
        } elseif ($request === 'todoDelete') {
            $todoController->delete();
        }

        //表示用データの取得
        $todoAscender = new TodoViewArranger(new TodoAscendingStrategy());
        $_SESSION['displayData'] = $todoAscender->arrangement();
        header('Location:view_top.php');
        exit();
    }


    //編集を行う際のデフォルト値を設定する処理
    if ($request === 'todoBeforeUpdate') {
        $_SESSION['default'] = $todoController->beforeUpdate();
        header('Location:view_edit.php');
        exit();
    }


    //todoテーブルの昇順、降順の切り替え

    //昇順
    if ($request === 'todoAscend') {
        $todoAscender = new TodoViewArranger(new TodoAscendingStrategy());
        $_SESSION['displayData'] = $todoAscender->arrangement();
        header('Location:view_top.php');
        exit();
    }

    //降順
    if ($request === 'todoDescend') {
        $todoDescender = new TodoViewArranger(new TodoDescendingStrategy());
        $_SESSION['displayData'] = $todoDescender->arrangement();
        header('Location:view_top.php');
        exit();
    }
} catch(Exception $e) {
    //DBの接続不良、$requestの改竄が起こったらエラー
    exit($e->getMessage());
}
