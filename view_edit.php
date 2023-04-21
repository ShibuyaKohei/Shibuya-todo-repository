<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>編集</title>
</head>
<body>

<?php
//DBで受け継いだデータを$recに格納、もしくは
?>
<h1>
    Edit Todo Page
</h1>
<form method="post" action="model.php">
    <div style="margin: 10px">
        <label for="title">タイトル：</label>
        <input id="title" type="text" name="title" value="<?php print $default['title']?>">
    </div>
    <div style="margin: 10px">
        <label for="content">内容：</label>
        <textarea id="content" name="content" rows="8" cols="40"><?php print $default['content']?></textarea>
    </div>
    <input name="id" type="hidden" value="<?php print $default['id'];?>">
    <input name="request" type="hidden" value="todoUpdate">
    <input type="submit" name="post" value="完了">
</form>
<form method="post" action="controller.php">
    <input name="request" type="hidden" value="todoAscend">
    <button type="submit" name="back">戻る</button>
</form>
</body>
</html>