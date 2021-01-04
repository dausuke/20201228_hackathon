<?php
session_start();
ini_set('display_errors', 1);
//いいね時のデータ
$uid = $_SESSION['uid'];
// DB接続の設定
include('functions.php');
$pdo = connect_to_db();
//いいねが押されたデータを取得
$color = 'color';
$favorite_sql = 'SELECT shopname,category,freetext,lat,lng FROM content_table WHERE favorite_userid=:userid AND class=:color';
$favorite_stmt = $pdo->prepare($favorite_sql);
$favorite_stmt->bindValue(':userid', $uid, PDO::PARAM_STR);
$favorite_stmt->bindValue(':color', $color, PDO::PARAM_STR);

//SQL実行
$favorite_status = $favorite_stmt->execute();

if ($favorite_status == false) {
    $error = $favorite_stmt->errorInfo();
    // データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
} else {
    // データ表示
    $favorite_result = $favorite_stmt->fetchAll(PDO::PARAM_STR);
    $favorite_mapData = array();
    foreach($favorite_result as $key=> $value){
        $favorite_mapData = array_merge($favorite_mapData,array($key=>$value));
    }
    echo json_encode($favorite_mapData,JSON_UNESCAPED_UNICODE);
    // var_dump($favorite_mapData);
    // exit();
}