<?php
//コントローラとビューは$_SESSIONでデータのやり取りをする
session_start();
session_regenerate_id(true);
include_once('model_Sanitization.php');
$sanitizer = new Sanitization();
$displayData = $sanitizer->sanitizeTwoDimensionalArray($_SESSION['displayData']);
session_unset();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDoリスト</title>
</head>
<body>

    <h1>ToDoリスト</h1>
    <form action="view_create.html">
        <button type="submit" style="padding: 10px;font-size: 16px;margin-bottom: 10px">新規作成</button>
    </form>
    <h4>タイトルをクリックすると編集が行えます</h4>
    <table border="1">
    <tr>
        <th>ID</th>
        <th>タイトル</th>
        <th>内容</th>
        <th>作成日時</th>
        <th>更新日時</th>
        <th>削除</th>
    </tr>

    <?php

    foreach ($displayData as $value) {
        ?>
    <tr>
        
        <td><?php echo $value['id'];?></td>
        <td>
            <form method="post" action="controller.php">
                <button type="submit" style="padding: 15px;font-size: 16px; color: blue;border: none;background: transparent;"><?php echo $value['title']?></button>
                <input name="id" type="hidden" value="<?php echo $value['id'];?>">
                <input name="request" type="hidden" value="todoBeforeUpdate">
            </form>
        </td>
        <td><?php echo $value['content']?></td>
        <td><?php echo $value['created_at']?></td>
        <td><?php echo $value['updated_at']?></td>
        <td>
            <form method="post" action="view_delete_check.php">
                <button type="submit" style="padding: 10px;font-size: 16px;">削除する</button>
                <input name="id" type="hidden" value="<?php echo $value['id'];?>">
            </form>
        </td>
    </tr>


    <?php
    }
?>

    </table>

    <form method= "post" action="controller.php">
        <select name="request">
        <option value="todoAscend">作成日時の昇順</option>
        <option value="todoDescend">更新日時の昇順</option>
        </select>
        <button type="submit">一覧表示</button>
    </form>
    
</body>
</html>