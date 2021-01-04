<?php
session_start();
ini_set('display_errors', 1);
$userid = $_SESSION['uid'];
$editemail = $_POST['email'];
$editaccountname = $_POST['accountname'];
$editcity = $_POST['city'];

// var_dump($_POST);
// exit();

// DB接続の設定
include('functions.php');
$pdo = connect_to_db();

//DBに保存られているユーザー情報を編集
$update_sql = 'UPDATE userdata_table SET accountname = :editaccountname, city = :editcity, email =:editemail WHERE id=:userid';

// SQL準備&実行
$update_stmt = $pdo->prepare($update_sql);
$update_stmt->bindValue(':editaccountname', $editaccountname, PDO::PARAM_STR);
$update_stmt->bindValue(':editcity', $editcity, PDO::PARAM_STR);
$update_stmt->bindValue(':editemail', $editemail, PDO::PARAM_STR);
$update_stmt->bindValue(':userid', $userid, PDO::PARAM_STR);
$update_status = $update_stmt->execute();

// データ登録処理後
if ($update_status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $update_stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
}

//content_tableに保存されているユーザーアカウント名も変更
$update_sql2 = 'UPDATE content_table SET accountname = :editaccountname WHERE userid=:userid';
$update_stmt2 = $pdo->prepare($update_sql2);
$update_stmt2->bindValue(':editaccountname', $editaccountname, PDO::PARAM_STR);
$update_stmt2->bindValue(':userid', $userid, PDO::PARAM_STR);
$update_status2 = $update_stmt2->execute();

if ($update_status2 == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $update_stmt2->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
}



//DBから投稿データ取得
$sql = 'SELECT accountname FROM userdata_table';
$stmt = $pdo->prepare($sql);
//SQL実行
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    // データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
} else {
    // データ表示
    $result = $stmt->fetchAll(PDO::PARAM_STR);
    $json_edit_data = json_encode($result, JSON_UNESCAPED_UNICODE);
    echo $json_edit_data;
    // var_dump($result[0]['shopname']);
    // exit();
}