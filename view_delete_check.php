<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>削除確認</title>
</head>
<body>
    <?php
    include_once('model_Sanitization.php');
    $sanitizer = new Sanitization();
    $sanitizedPost = $sanitizer->sanitize($_POST);
    ?>
    <p>本当に削除しますか？</p>
    <form method="post" action="controller.php">
        <input name="id" type="hidden" value="<?php echo $sanitizedPost['id'];?>">
        <input name="request" type="hidden" value="todoDelete">
        <button type="submit">はい</button>
    </form>
    <form method="post" action="controller.php">
        <input name="request" type="hidden" value="todoAscend">
        <button type="submit">いいえ</button>
    </form>
</body>
</html>