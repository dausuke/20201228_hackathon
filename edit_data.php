<?php
session_start();
//ユーザー情報取得
$uid = $_SESSION["uid"];
ini_set('display_errors', 1);

$content_id = $_POST['content_id'];
$edit_evaluation = $_POST['edit_evaluation'];
$edit_freetext = $_POST['edit_freetext'];

// var_dump($_POST);
// exit();

// DB接続の設定
include('functions.php');
$pdo = connect_to_db();

if($edit_evaluation && $edit_freetext){
    $edit_sql = 'UPDATE content_table SET evaluation=:edit_evaluation, freetext=:edit_freetext WHERE id=:content_id';
    $edit_stmt = $pdo->prepare($edit_sql);
    $edit_stmt->bindValue(':content_id', $content_id, PDO::PARAM_STR);
    $edit_stmt->bindValue(':edit_evaluation', $edit_evaluation, PDO::PARAM_STR);
    $edit_stmt->bindValue(':edit_freetext', $edit_freetext, PDO::PARAM_STR);
    $edit_status = $edit_stmt->execute();
}else if($edit_freetext){
    $edit_sql = 'UPDATE content_table SET freetext=:edit_freetext WHERE id=:content_id';
    $edit_stmt = $pdo->prepare($edit_sql);
    $edit_stmt->bindValue(':content_id', $content_id, PDO::PARAM_STR);
    $edit_stmt->bindValue(':edit_freetext', $edit_freetext, PDO::PARAM_STR);
    $edit_status = $edit_stmt->execute();
}else if($edit_evaluation){
    $edit_sql = 'UPDATE content_table SET evaluation=:edit_evaluation WHERE id=:content_id';
    $edit_stmt = $pdo->prepare($edit_sql);
    $edit_stmt->bindValue(':content_id', $content_id, PDO::PARAM_STR);
    $edit_stmt->bindValue(':edit_evaluation', $edit_evaluation, PDO::PARAM_STR);
    $edit_status = $edit_stmt->execute();
}else{
    $result = "noedit";
    echo $result;
    exit();
};

// データ登録処理後
if ($edit_status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $edit_stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
}

//DBから投稿データ取得
$content_sql = 'SELECT * FROM content_table ORDER BY getday DESC';
$content_stmt = $pdo->prepare($content_sql);
//SQL実行
$content_status = $content_stmt->execute();

if ($content_status == false) {
    $error = $content_stmt->errorInfo();
    // データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
} else {
    // データ表示
    $content_result = $content_stmt->fetchAll(PDO::PARAM_STR);
    $json_edit_data = json_encode($content_result, JSON_UNESCAPED_UNICODE);
    echo $json_edit_data;
    // var_dump($result[0]['shopname']);
    // exit();
}



