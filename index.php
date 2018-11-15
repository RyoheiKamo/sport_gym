<?php

require_once("./config/dbProperties.php");
require_once('./class/business/getFormAction.php');

$action = new getFormAction();

$event_command = null;

$user_id = null;

//イベントコマンド取得
if (isset($_POST['event_command'])) {
    $event_command = $_POST['event_command'];
}

switch ($event_command) {

    //初回ログイン
    case 'login_first':
        $action->getLoginFirst($_POST);
        require("./view/login.php");
        break;

    //ログイン
    case 'login':
        $action->getLogin($_POST);
        require("./view/post.php");
        break;

    // DBsave
    case 'save':
        $last_date = $action->getPostLastDate($user_id)['created_at'];

        if (date('Y-m-d', time()) != substr($last_date, 0, 10)) {
            $action->setPhysicalData($_POST);
            require("./view/data_list.php");
        } else {
            $action->updatePhysicalData($data_id, $_POST);
            require("./view/data_list.php");
        }
        break;

    // data list
    case 'data_list':
        require("./view/data_list.php");
        break;

    // データ更新画面
    case 'edit':
        $data_id = $_POST['$data_id'];
        require("./view/data_detail.php");
        break;

    // データ更新
    case 'edit_save':
        $action->updatePhysicalData($data_id, $_POST);
        require("./view/data_list.php");
        break;

    // データ削除
    case 'delete':
        $data_id = $_POST['$data_id'];
        $action->deletePhysicalData($data_id);
        require("./view/data_list.php");
        break;

    // 投稿に戻る
    case 'back_to_post':
        require("./view/post.php");
        break;

    // ログアウト
    case 'logout':
        $action->getLogout();
        require("./view/login.php");
        break;

    default:
        require("./view/login.php");
        break;

}

