<?php
session_start();
ini_set('display_errors', 1);

//ユーザー情報取得
$uid = $_SESSION["uid"];

// DB接続の設定
include('functions.php');
$pdo = connect_to_db();
//DBからデータ取得
$sql = 'SELECT shopname,category,freetext,lat,lng,userid FROM content_table';
$stmt = $pdo->prepare($sql);
//SQL実行
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    // データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
} else {
    // // データ表示
    $result = $stmt->fetchAll(PDO::PARAM_STR);
    $mapData = array();
    foreach($result as $key=> $value){
        if($uid==$value['userid']){
            $mapData = array_merge($mapData,array($key=>$value));
        }
    }
    echo json_encode($mapData,JSON_UNESCAPED_UNICODE);
    // var_dump($result);
    // exit();

}
