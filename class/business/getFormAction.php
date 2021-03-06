<?php

class getFormAction
{

    public $pdo;

    const FLAG_ON = 1;

    const FLAG_OFF = 0;

    /**
     * コネクション確保
     */
    function __construct()
    {
        date_default_timezone_set('Asia/Tokyo');

        try {
            $this->pdo = new PDO(
                PDO_DSN,
                DATABASE_USER,
                DATABASE_PASSWORD,
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                )
            );
            $this->pdo->prepare('use saitamacode_wpdemo')->execute();
        } catch (PDOException $e) {
            echo 'データベースにアクセスできませんでした。' . $e->getMessage();
            die();
        }
    }

    /**
     * ログインデータを新規作成する
     *
     */
    function signUp()
    {
        if (empty($_POST["member_id"])) {  // 値が空のとき
            echo $errorMessage = 'ユーザーIDが未入力です。';
        } else if (empty($_POST["password"])) {
            echo $errorMessage = 'パスワードが未入力です。';
        }

        if (!empty($_POST["member_id"]) && !empty($_POST["password"])) {
            // 入力したユーザIDとパスワードを格納
            $member_id = $_POST["member_id"];
            $password = $_POST["password"];

            try {
                $smt = $this->pdo->prepare(
                    'INSERT INTO user_users (member_id, password) VALUES (:member_id, :password)'
                );
                $smt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
                $smt->bindParam(':password', $password, PDO::PARAM_INT);
                $smt->execute();

                echo $signUpMessage = '登録が完了しました。';  //
            } catch (PDOException $e) {
                echo $errorMessage = 'データベースエラー';
            }
        }
    }

    /**
     * ニックネームとパスワードを確認する
     *
     */
    function getLogin()
    {
        session_start(['cookie_lifetime' => 3600,]);

        // エラーメッセージの初期化
        $errorMessage = "";

        // 1. ユーザIDの入力チェック
        if (empty($_POST["member_id"])) {  // emptyは値が空のとき
            echo $errorMessage = '会員IDが未入力です。';
            exit;
        } else if (empty($_POST["password"])) {
            echo $errorMessage = 'パスワードが未入力です。';
            exit;
        }

        if (!empty($_POST["member_id"]) && !empty($_POST["password"])) {
            // 入力したユーザIDを格納
            $member_id = $_POST["member_id"];
            $password = $_POST["password"];

            $statusOn = self::FLAG_ON;

            try {
                $smt = $this->pdo->prepare(
                    'SELECT user_id, member_id FROM user_users WHERE password = :password AND status = :status'
                );
                $smt->bindParam(':password', $password, PDO::PARAM_INT);
                $smt->bindParam(':status', $statusOn, PDO::PARAM_INT);
                $smt->execute();

                // 実行結果を配列に返す。
                $result = $smt->fetchAll(PDO::FETCH_ASSOC);

                if ($result[0]['member_id'] == null){
                    $this->getLoginFix($member_id, $password);
                } elseif($result[0]['member_id'] != $member_id) {
                    echo $errorMessage = '会員IDとパスワードの組み合わせが異なります。';
                    exit;
                }

                if ($result[0]['user_id'] == null){
                    echo $errorMessage = 'ログインに失敗しました。';
                    exit;
                }

                $_SESSION['USER_ID'] = $result[0]['user_id'];

            } catch (PDOException $e) {
                echo 'データベースエラー' . $e->getMessage();
            }
        }
    }

    /**
     * 暫定修正用:
     *
     */
    function getLoginFix($member_id, $password)
    {
        try {
            // 登録データ取得
            $smt = $this->pdo->prepare(
                'UPDATE `user_users` SET member_id = :member_id WHERE password = :password'
            );
            $smt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
            $smt->bindParam(':password', $password, PDO::PARAM_INT);
            $smt->execute();

        } catch (PDOException $e) {
            echo '修正エラーです。' . $e->getMessage();
        }
    }

    /**
     * ログアウトする
     *
     */
    function getLogout()
    {
        //セッションの終了
        session_start();

        if (isset($_SESSION["NAME"])) {
            echo $errorMessage = "ログアウトしました。";
        } else {
            echo $errorMessage = "セッションがタイムアウトしました。";
        }

        // セッションの変数のクリア
        $_SESSION = array();

        // セッションクリア
        @session_destroy();
    }

    /**
     * 最終更新日を取得する
     *
     * @return array
     */
    function getPostLastDate()
    {
        session_start();

        try {
            // データの保存
            $smt = $this->pdo->prepare(
                'SELECT data_id, created_at FROM user_physical_datas WHERE user_id = :user_id ORDER BY created_at DESC limit 1'
            );
            $smt->bindParam(':user_id', $_SESSION['USER_ID'], PDO::PARAM_INT);
            $smt->execute();

            // 実行結果を配列に返す。
            $result = $smt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            echo '最終投稿日時が取得ができませんでした。' . $e->getMessage();
        }
    }

    /**
     * データをDBに保存
     *
     */
    function setPhysicalData()
    {
        session_start();

        try {
            // データの保存
            $smt = $this->pdo->prepare(
                'INSERT INTO user_physical_datas (user_id,weight,fat_percentage,muscle_mass,water_content,visceral_fat,basal_metabolic_rate,bmi,created_at,updated_at, delete_flag) VALUES (:user_id,:weight,:fat_percentage,:muscle_mass,:water_content,:visceral_fat,:basal_metabolic_rate,:bmi,now(),now(),0)'
            );
            $smt->bindParam(':user_id', $_SESSION['USER_ID'], PDO::PARAM_INT);
            $smt->bindParam(':weight', $_POST['weight'], PDO::PARAM_STR);
            $smt->bindParam(':fat_percentage', $_POST['fat_percentage'], PDO::PARAM_STR);
            $smt->bindParam(':muscle_mass', $_POST['muscle_mass'], PDO::PARAM_STR);
            $smt->bindParam(':water_content', $_POST['water_content'], PDO::PARAM_STR);
            $smt->bindParam(':visceral_fat', $_POST['visceral_fat'], PDO::PARAM_STR);
            $smt->bindParam(':basal_metabolic_rate', $_POST['basal_metabolic_rate'], PDO::PARAM_STR);
            $smt->bindParam(':bmi', $_POST['bmi'], PDO::PARAM_STR);
            $smt->execute();

        } catch (PDOException $e) {
            echo 'データの入力ができませんでした。' . $e->getMessage();
        }
    }

    /**
     * データを更新する
     *
     * @param int $data_id
     */
    function updatePhysicalData($data_id)
    {
        try {
            // データの更新
            $smt = $this->pdo->prepare(
                'UPDATE user_physical_datas SET weight = :weight,fat_percentage = :fat_percentage,muscle_mass = :muscle_mass,water_content = :water_content,visceral_fat = :visceral_fat,basal_metabolic_rate = :basal_metabolic_rate,bmi = :bmi,updated_at = now() WHERE data_id = :data_id'
            );
            $smt->bindParam(':weight', $_POST['weight'], PDO::PARAM_STR);
            $smt->bindParam(':fat_percentage', $_POST['fat_percentage'], PDO::PARAM_STR);
            $smt->bindParam(':muscle_mass', $_POST['muscle_mass'], PDO::PARAM_STR);
            $smt->bindParam(':water_content', $_POST['water_content'], PDO::PARAM_STR);
            $smt->bindParam(':visceral_fat', $_POST['visceral_fat'], PDO::PARAM_STR);
            $smt->bindParam(':basal_metabolic_rate', $_POST['basal_metabolic_rate'], PDO::PARAM_STR);
            $smt->bindParam(':bmi', $_POST['bmi'], PDO::PARAM_STR);
            $smt->bindParam(':data_id', $data_id, PDO::PARAM_INT);
            $smt->execute();

        } catch (PDOException $e) {
            echo 'データの更新が出来ませんでした。' . $e->getMessage();
        }
    }

    /**
     * データリストをDBから読み込み
     *
     * @param int $user_id
     * @param int $number
     * @return array
     */
    function getPhysicalDataList($user_id, $number)
    {
        try {
            $date = new DateTime();

            $now_date = $date->format('Y-m-d H:i:s');
            $before_date = $date->modify('-'.$number.' days')->format('Y-m-d H:i:s');

            // 登録データ取得
            $smt = $this->pdo->prepare(
                'SELECT * FROM user_physical_datas WHERE user_id = :user_id AND delete_flag = 0 AND created_at BETWEEN :before_date AND :now_date ORDER BY created_at DESC'
            );
            $smt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $smt->bindParam(':now_date', $now_date, PDO::PARAM_STR);
            $smt->bindParam(':before_date', $before_date, PDO::PARAM_STR);
            $smt->execute();
            // 実行結果を配列に返す。
            $result = $smt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            echo 'データの読み込みが出来ませんでした。' . $e->getMessage();
        }
    }

    /**
     * データをDBから読み込み
     *
     * @param int $data_id
     * @return array
     */
    function getPhysicalData($data_id)
    {
        try {
            // 登録データ取得
            $smt = $this->pdo->prepare(
                'SELECT * FROM user_physical_datas WHERE data_id = :data_id AND delete_flag = 0'
            );
            $smt->bindParam(':data_id', $data_id, PDO::PARAM_INT);
            $smt->execute();
            // 実行結果を配列に返す。
            $result = $smt->fetchAll(PDO::FETCH_ASSOC);

            return $result;

        } catch (PDOException $e) {
            echo 'データの読み込みが出来ませんでした。' . $e->getMessage();
        }
    }

    /**
     * データを論理削除する
     *
     * @param int $data_id
     */
    function deletePhysicalData($data_id)
    {
        try {
            // 登録データ論理削除
            $smt = $this->pdo->prepare(
                'UPDATE user_physical_datas SET delete_flag = 1 WHERE data_id = :data_id'
            );
            $smt->bindParam(':data_id', $data_id, PDO::PARAM_INT);
            $smt->execute();

        } catch (PDOException $e) {
            echo 'データの削除が出来ませんでした。' . $e->getMessage();
        }
    }
}
