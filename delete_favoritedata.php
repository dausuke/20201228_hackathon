<?php
session_start();
ini_set('display_errors', 1);

$edit_favorite_id = $_POST['content_id'];
// var_dump($delete_favorite_id);
// exit();

// DB接続の設定
include('functions.php');
$pdo = connect_to_db();

//content_tableのclassカラムの編集
//すでにDBに保存られているクラス情報を編集
$edit_sql = 'UPDATE content_table SET class="",favorite_userid="" WHERE id=:edit_favorite_id';

// SQL準備&実行
$edit_stmt = $pdo->prepare($edit_sql);
$edit_stmt->bindValue(':edit_favorite_id', $edit_favorite_id, PDO::PARAM_STR);
$edit_status = $edit_stmt->execute();

// データ登録処理後
if ($edit_status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $edit_stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
}