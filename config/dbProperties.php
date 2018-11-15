<?php
//接続データベース情報(本番)
//記述例なので環境に応じて変更をしてください。
define('DATABASE_NAME','saitamacode_wpdemo');
define('DATABASE_USER','saitamacode_kamo');
define('DATABASE_PASSWORD','triumph1');
define('DATABASE_HOST','mysql5005.xserver.jp');

define('PDO_DSN','mysql:host=DATABASE_HOST' . DATABASE_NAME .';host=' . DATABASE_HOST);
