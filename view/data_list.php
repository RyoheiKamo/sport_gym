<?php

session_start();
// 登録データ取得
$data_list_7 = $action->getPhysicalDataList($_SESSION['USER_ID'], 7);

$data_list_30 = $action->getPhysicalDataList($_SESSION['USER_ID'], 30);

$data_list_180 = $action->getPhysicalDataList($_SESSION['USER_ID'], 180);

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
<?php if (!empty($data_list_7)) { ?>
    <table width="100%" border="1">
        <tr>
        </tr>
        <?php foreach ($data_list_7 as $post) { ?>
            <tr>
                <td><?php
                    if ($post["weight"] == 0.0){$post["weight"] = null;}
                    echo nl2br($post["weight"]); ?></td>
                <td><?php
                    if ($post["fat_percentage"] == 0.0){$post["fat_percentage"] = null;}
                    echo nl2br($post["fat_percentage"]); ?></td>
                <td><?php
                    if ($post["muscle_mass"] == 0.0){$post["muscle_mass"] = null;}
                    echo nl2br($post["muscle_mass"]); ?></td>
                <td><?php
                    if ($post["water_content"] == 0.0){$post["water_content"] = null;}
                    echo nl2br($post["water_content"]); ?></td>
                <td><?php
                    if ($post["visceral_fat"] == 0.0){$post["visceral_fat"] = null;}
                    echo nl2br($post["visceral_fat"]); ?></td>
                <td><?php
                    if ($post["basal_metabolic_rate"] == 0.0){$post["basal_metabolic_rate"] = null;}
                    echo nl2br($post["basal_metabolic_rate"]); ?></td>
                <td><?php
                    if ($post["bmi"] == 0.0){$post["bmi"] = null;}
                    echo nl2br($post["bmi"]); ?></td>
                <td><?php echo date('m/d',strtotime($post["created_at"])); ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>
<?php if (!empty($data_list_30)) { ?>
    <table width="100%" border="1">
        <tr>
        </tr>
        <?php foreach ($data_list_30 as $post) { ?>
            <tr>
                <td><?php
                    if ($post["weight"] == 0.0){$post["weight"] = null;}
                    echo nl2br($post["weight"]); ?></td>
                <td><?php
                    if ($post["fat_percentage"] == 0.0){$post["fat_percentage"] = null;}
                    echo nl2br($post["fat_percentage"]); ?></td>
                <td><?php
                    if ($post["muscle_mass"] == 0.0){$post["muscle_mass"] = null;}
                    echo nl2br($post["muscle_mass"]); ?></td>
                <td><?php
                    if ($post["water_content"] == 0.0){$post["water_content"] = null;}
                    echo nl2br($post["water_content"]); ?></td>
                <td><?php
                    if ($post["visceral_fat"] == 0.0){$post["visceral_fat"] = null;}
                    echo nl2br($post["visceral_fat"]); ?></td>
                <td><?php
                    if ($post["basal_metabolic_rate"] == 0.0){$post["basal_metabolic_rate"] = null;}
                    echo nl2br($post["basal_metabolic_rate"]); ?></td>
                <td><?php
                    if ($post["bmi"] == 0.0){$post["bmi"] = null;}
                    echo nl2br($post["bmi"]); ?></td>
                <td><?php echo date('m/d',strtotime($post["created_at"])); ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>
<?php if (!empty($data_list_180)) { ?>
    <table width="100%" border="1">
        <tr>
        </tr>
        <?php foreach ($data_list_180 as $post) { ?>
            <tr>
                <td><?php
                    if ($post["weight"] == 0.0){$post["weight"] = null;}
                    echo nl2br($post["weight"]); ?></td>
                <td><?php
                    if ($post["fat_percentage"] == 0.0){$post["fat_percentage"] = null;}
                    echo nl2br($post["fat_percentage"]); ?></td>
                <td><?php
                    if ($post["muscle_mass"] == 0.0){$post["muscle_mass"] = null;}
                    echo nl2br($post["muscle_mass"]); ?></td>
                <td><?php
                    if ($post["water_content"] == 0.0){$post["water_content"] = null;}
                    echo nl2br($post["water_content"]); ?></td>
                <td><?php
                    if ($post["visceral_fat"] == 0.0){$post["visceral_fat"] = null;}
                    echo nl2br($post["visceral_fat"]); ?></td>
                <td><?php
                    if ($post["basal_metabolic_rate"] == 0.0){$post["basal_metabolic_rate"] = null;}
                    echo nl2br($post["basal_metabolic_rate"]); ?></td>
                <td><?php
                    if ($post["bmi"] == 0.0){$post["bmi"] = null;}
                    echo nl2br($post["bmi"]); ?></td>
                <td><?php echo date('m/d',strtotime($post["created_at"])); ?></td>
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
