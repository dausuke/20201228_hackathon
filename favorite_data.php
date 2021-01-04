<?php
session_start();
ini_set('display_errors', 1);
//いいね時のデータ
$favorite_uid = $_SESSION['uid'];
$favorite_class = $_POST["favorite_class"];
$favorite_id = $_POST["favorite_id"];

// var_dump($_POST);
// exit();

// DB接続の設定
include('functions.php');
$pdo = connect_to_db();

//DBに保存られているクラス情報を編集
$update_sql = 'UPDATE content_table SET class=:favorite_class,favorite_userid=:favorite_uid WHERE id=:favorite_id';

// SQL準備&実行
$update_stmt = $pdo->prepare($update_sql);
$update_stmt->bindValue(':favorite_class', $favorite_class, PDO::PARAM_STR);
$update_stmt->bindValue(':favorite_id', $favorite_id, PDO::PARAM_STR);
$update_stmt->bindValue(':favorite_uid', $favorite_uid, PDO::PARAM_STR);
$update_status = $update_stmt->execute();

// データ登録処理後
if ($update_status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $update_stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
}
