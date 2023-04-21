<?php
session_start();
session_regenerate_id(true);
include_once('model_Sanitization.php');
$sanitizer = new Sanitization();
$default = $sanitizer->sanitize($_SESSION['default'][0]);
session_unset();
?>

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


<h1>
    Edit Todo Page
</h1>
<form method="post" action="controller.php">
    <div style="margin: 10px">
        <label for="title">タイトル：</label>
        <input id="title" type="text" name="title" value="<?php echo $default['title']?>">
    </div>
    <div style="margin: 10px">
        <label for="content">内容：</label>
        <textarea id="content" name="content" rows="8" cols="40"><?php echo $default['content']?></textarea>
    </div>
    <input name="id" type="hidden" value="<?php echo $default['id'];?>">
    <input name="request" type="hidden" value="todoUpdate">
    <input type="submit" name="post" value="完了">
</form>
<form method="post" action="controller.php">
    <button type="submit" name="request" value="todoAscend">戻る</button>
</form>
</body>
</html>