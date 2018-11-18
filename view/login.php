<?php

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
<hr>
<p>ログイン</p>
<!-- エラーエリア -->
<?php if (!empty($errm)) {?>
    <div class="error">
        <?php foreach($errm as $key => $value) {
            echo $value;
        }?>
    </div>
<?php }?>
<form action="./index.php" method="post">
    <input type="number" name="member_id">
    <input type="number" name="password">
    <input type="hidden" name="event_command" value="login">
    <input type="submit" value="ログイン">
</form>
<form action="./index.php" method="post">
    <input type="number" name="member_id">
    <input type="number" name="password">
    <input type="hidden" name="event_command" value="signUp">
    <input type="submit" value="初回ログイン">
</form>

</body>
</html>
