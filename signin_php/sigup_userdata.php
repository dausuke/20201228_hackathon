<?php
ini_set( 'display_errors', 1 );
ini_set( 'error_reporting', E_ALL );
//データ受信
$username = $_POST['name'];
$accountname = $_POST['accountname'];
$age = $_POST['age'];
$city = $_POST['city'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$userpassword = $_POST['password'];

// var_dump($_POST);
// exit();

// DB接続の設定
include('../functions.php');
$pdo = connect_to_db();

$sql = 'INSERT INTO userdata_table(id,username,accountname,age,gender,city,email,userpassword,created_at) VALUES(NULL,:username,:accountname,:age,:gender,:city,:email,:userpassword,sysdate())';
$stmt = $pdo->prepare($sql);
//バインド変数設定
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':accountname', $accountname, PDO::PARAM_STR);
$stmt->bindValue(':age', $age, PDO::PARAM_STR);
$stmt->bindValue(':city', $city, PDO::PARAM_STR);
$stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':userpassword', $userpassword, PDO::PARAM_STR);
//SQL実行
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    // データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
} else {
    // 登録ページへ移動
    header('Location:../index.php');
}
