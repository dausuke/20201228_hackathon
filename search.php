<?php
session_start();
ini_set('display_errors', 1);

// var_dump($_POST);
// exit();
$keyword = $_POST['keyword'];
$keyword = '%'.$keyword.'%';



// DB接続の設定
include('functions.php');
$pdo = connect_to_db();
//DBからデータ取得
$sql = 'SELECT * FROM content_table WHERE shopname LIKE :keyword or  area LIKE :keyword or  category LIKE :keyword or  freetext LIKE :keyword';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
//SQL実行
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    // データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
} else {
    // データ表示
    $result = $stmt->fetchAll(PDO::PARAM_STR);
    // var_dump($result);
    // exit();
    echo json_encode($result,JSON_UNESCAPED_UNICODE);

}
?>