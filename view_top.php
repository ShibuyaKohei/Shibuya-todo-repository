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
    //コントローラとビューは$_SESSIONでデータのやり取りをする
    session_start();
    session_regenerate_id(true);

    foreach ($displayData as $value) {
        ?>
    <tr>
        
        <td><?php echo $value['id'];?></td>
        <td><?php echo $value['title']?></td>
        <td><?php echo $value['content']?></td>
        <td><?php echo $value['created_at']?></td>
        <td><?php echo $value['updated_at']?></td>
        <td>
            <form method="post" action="controller.php">
                <button type="submit" style="padding: 10px;font-size: 16px;">編集する</button>
                <input name="id" type="hidden" value="<?php echo $value['id'];?>">
                <input name="request" type="hidden" value="todoBeforeUpdate">
            </form>
        </td>
        <td>
            <form method="post" action="view_delete_check.php">
                <button type="submit" style="padding: 10px;font-size: 16px;">削除する</button>
                <input name="id" type="hidden" value="<?php echo $value['id'];?>">
                <input name="request" type="hidden" value="todoDelete">
            </form>
        </td>
    </tr>


    <?php
    }
    ?>

    </table>

    <form method= "post" action="controller.php">
        <select name="request">
        <option value="todoAscend">昇順</option>
        <option value="todoDescend">降順</option>
        </select>
        <button type="submit">一覧表示</button>
    </form>
    
</body>
</html>