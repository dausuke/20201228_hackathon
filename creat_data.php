<?php
session_start();
$uid = $_SESSION["uid"];
ini_set('display_errors', 1);

// DB接続の設定
include('functions.php');
$pdo = connect_to_db();

//ログ保存時のデータ
$shopname = $_POST['name'];
$area = $_POST['area'];
$evaluation = $_POST['evaluation'];
$category = $_POST['category'];
$freetext = $_POST['freetext'];
$userid = $_SESSION["uid"];
$mapdata = $_SESSION["mapdata"];        //APIで返されたデータを取得
//候補地なしの場合はlatlngは空文字
if($_POST['candidate']=='nodata'){
    $lat = 0;
    $lng =0;
}else{
    //$shopnameが含まれるデータのキーを$mapdataから検索、キー取得
    $mapkey = array_search($shopname,array_column($mapdata,'name'));
    $lat = $mapdata[$mapkey]['geometry']['location']['lat'];
    $lng = $mapdata[$mapkey]['geometry']['location']['lng'];
}
// var_dump($lat);
// exit();

//accountname設定
//ログインしているuidからアカウント名取得
//DBからユーザーデータ取得
$sql_userData = 'SELECT id,accountname FROM userdata_table';
$stmt_userData = $pdo->prepare($sql_userData);
//SQL実行
$status_userData = $stmt_userData->execute();

if ($status_userData == false) {
    $error = $stmt_userData->errorInfo();
    // データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
} else {
    // データ表示
    $result_userData = $stmt_userData->fetchAll(PDO::PARAM_STR);
    // var_dump($result_userData);
    // exit();
    foreach($result_userData as $value){
        if($uid==$value['id']){
            $accountname = $value['accountname'];
        }
    }
    // var_dump($accountname);
    //     exit();
}

//ログ保存時
$sql = 'INSERT INTO content_table(id,userid,accountname,favorite_userid,shopname,area,evaluation,category,freetext,getday,class,lat,lng) VALUES(NULL,:userid,:accountname,"",:shopname,:area,:evaluation,:category,:freetext,sysdate(),"",:lat,:lng)';
$stmt = $pdo->prepare($sql);
//バインド変数設定
$stmt->bindValue(':userid', $userid, PDO::PARAM_STR);
$stmt->bindValue(':accountname', $accountname, PDO::PARAM_STR);
$stmt->bindValue(':shopname', $shopname, PDO::PARAM_STR);
$stmt->bindValue(':area', $area, PDO::PARAM_STR);
$stmt->bindValue(':evaluation', $evaluation, PDO::PARAM_STR);
$stmt->bindValue(':category', $category, PDO::PARAM_STR);
$stmt->bindValue(':freetext', $freetext, PDO::PARAM_STR);
$stmt->bindValue(':lat', $lat, PDO::PARAM_STR);
$stmt->bindValue(':lng', $lng, PDO::PARAM_STR);
//SQL実行
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
// データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
}else{
    //データ登録後、セッションのマップデータ初期化
    unset($_SESSION['mapdata']);
}

?>