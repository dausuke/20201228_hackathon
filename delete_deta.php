<?php
session_start();
ini_set('display_errors', 1);

$delete_id = $_POST['content_id'];
// var_dump($_POST);
// exit();

// DB接続の設定
include('functions.php');
$pdo = connect_to_db();

$sql = 'DELETE FROM content_table WHERE id=:delete_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':delete_id', $delete_id, PDO::PARAM_STR);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
}