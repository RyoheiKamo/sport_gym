<?php

class getFormAction
{

    public $pdo;

    /**
     * コネクション確保
     */
    function __construct()
    {
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
        } else if (empty($_POST["password"])) {
            echo $errorMessage = 'パスワードが未入力です。';
        }

        if (!empty($_POST["member_id"]) && !empty($_POST["password"])) {
            // 入力したユーザIDを格納
            $member_id = $_POST["member_id"];
            $password = $_POST["password"];

            // 2. エラー処理
            try {
                $smt = $this->pdo->prepare(
                    'SELECT user_id FROM user_users WHERE member_id = :member_id AND password = :password'
                );
                $smt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
                $smt->bindParam(':password', $password, PDO::PARAM_INT);
                $smt->execute();

                // 実行結果を配列に返す。
                $result = $smt->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION['USER_ID'] = $result[0]['user_id'];


            } catch (PDOException $e) {
                echo 'ログインに失敗しました。' . $e->getMessage();
            }
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
     * @param int $user_id
     * @return array
     */
    function getPostLastDate($user_id)
    {
        $user_id_num = (int)$user_id;

        try {
            // データの保存
            $smt = $this->pdo->prepare(
                'SELECT data_id, created_at FROM user_physical_datas WHERE user_id = :user_id ORDER BY created_at DESC limit 1'
            );
            $smt->bindParam(':user_id', $user_id_num, PDO::PARAM_INT);
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
                'UPDATE  user_physical_datas SET weight = :weight,fat_percentage = :fat_percentage,muscle_mass = :muscle_mass,water_content = :water_content,visceral_fat = :visceral_fat,basal_metabolic_rate = :basal_metabolic_rate,bmi = :bim,updated_at = now() WHERE data_id = data_id)'
            );
            $smt->bindParam(':data_id', $data_id, PDO::PARAM_INT);
            $smt->bindParam(':weight', $_POST['weight'], PDO::PARAM_STR);
            $smt->bindParam(':fat_percentage', $_POST['fat_percentage'], PDO::PARAM_STR);
            $smt->bindParam(':muscle_mass', $_POST['muscle_mass'], PDO::PARAM_STR);
            $smt->bindParam(':water_content', $_POST['water_content'], PDO::PARAM_STR);
            $smt->bindParam(':visceral_fat', $_POST['visceral_fat'], PDO::PARAM_STR);
            $smt->bindParam(':basal_metabolic_rate', $_POST['basal_metabolic_rate'], PDO::PARAM_STR);
            $smt->bindParam(':bmi', $_POST['bmi'], PDO::PARAM_STR);
            $smt->execute();

        } catch (PDOException $e) {
            echo 'データの更新が出来ませんでした。' . $e->getMessage();
        }

    }

    /**
     * データリストをDBから読み込み
     *
     * @param int $user_id
     * @return array
     */
    function getPhysicalDataList($user_id)
    {
        try {
            // 登録データ取得
            $smt = $this->pdo->prepare(
                'SELECT * FROM user_physical_datas WHERE user_id = :user_id AND delete_flag = 0 ORDER BY created_at DESC limit 20'
            );
            $smt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
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
                'SELECT * FROM user_physical_datas WHERE data_id = :data_id AND delete_flag = 0 ORDER BY created_at DESC limit 20'
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
