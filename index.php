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
    case 'signUp':
        $action->signUp();
        require("./view/login.php");
        break;

    //ログイン
    case 'login':
        $action->getLogin();
        require("./view/post.php");
        break;

    // DBsave
    case 'save':
        //最後の投稿データを取得する
        $last_post_data = $action->getPostLastDate();

        if (isset($last_post_data)) {
            if (date('Y-m-d', time()) == substr($last_post_data[0]['created_at'], 0, 10)) {
                //最後の投稿データの日付が本日と同一の場合ばアップデート
                $action->updatePhysicalData($last_post_data[0]['data_id']);
                require("./view/post.php");
                //それ以外の場合は新規作成
            } else {
                $action->setPhysicalData();
                require("./view/post.php");
            }
        } else {
            $action->setPhysicalData();
            require("./view/post.php");
        }
        break;

//    case 'save':
//        $action->setPhysicalData();
//        require("./view/post.php");
//        break;

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
        $action->updatePhysicalData($data_id);
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

