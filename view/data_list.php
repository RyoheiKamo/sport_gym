<?php

session_start();
// 登録データ取得
$data_list = $action->getPhysicalDataList($_SESSION['USER_ID']);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>フィジカルデータ一覧</title>
</head>
<body>
<h1>フィジカルデータ一覧</h1>
<!-- 投稿表示エリア -->
<?php if (!empty($data_list)) { ?>
    <table width="100%" border="1">
        <tr>
        </tr>
        <?php foreach ($data_list as $post) { ?>
            <tr>
                <td><?php echo nl2br($post["weight"]); ?></td>
                <td><?php echo nl2br($post["fat_percentage"]); ?></td>
                <td><?php echo nl2br($post["muscle_mass"]); ?></td>
                <td><?php echo nl2br($post["water_content"]); ?></td>
                <td><?php echo nl2br($post["visceral_fat"]); ?></td>
                <td><?php echo nl2br($post["basal_metabolic_rate"]); ?></td>
                <td><?php echo nl2br($post["bmi"]); ?></td>
                <td><?php echo $post["created_at"]; ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>
<!-- // 投稿表示エリア -->
<form action="./index.php" method="post">
    <input type="hidden" name="event_command" value="back_to_post">
    <input type="submit" value="投稿に戻る">
</form>
</body>
</html>
