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

    <table border="1">
    <tr>
        <th>ID</th>
        <th>タイトル</th>
        <th>内容</th>
        <th>作成日時</th>
        <th>更新日時</th>
        <th>編集</th>
        <th>削除</th>
    </tr>

    <?php

foreach ($displayData as $value) {
    ?>
    <tr>
        
        <td><?php echo $value['ID'];?></td>
        <td><?php echo $value['title']?></td>
        <td><?php echo $value['content']?></td>
        <td><?php echo $value['created_at']?></td>
        <td><?php echo $value['updated_at']?></td>
        <td>
            <form method="post" action="edit.php">
                <button type="submit" style="padding: 10px;font-size: 16px;" name="id" value="<?php print $value['ID']?>">編集する</button>
            </form>
        </td>
        <td>
            <form method="post" action="model.php">
                <button type="submit" style="padding: 10px;font-size: 16px;">削除する</button>
                <input name="id" type="hidden" value="<?php print $value['ID'];?>">
                <input name="request" type="hidden" value="delete">
            </form>
        </td>
    </tr>


    <?php
}
    ?>

    </table>

    <form method= "post" action="controller.php">
        <select name="arrange">
        <option value="ascend">昇順</option>
        <option value="descend">降順</option>
        </select>
    </form>
    
</body>
</html>