<?php
session_start();

//データ受信
$email = $_POST['email'];
$password = $_POST['pass'];

// var_dump($get_data);
// exit();

// DB接続の設定
include('../functions.php');
$pdo = connect_to_db();

//ログイン情報取得
$sql = 'SELECT id,email,userpassword FROM userdata_table';

$stmt = $pdo->prepare($sql);
//SQL実行
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    // データ登録失敗次にエラーを表示
    exit('sqlError:' . $error[2]);
} else {
    // データ表示
    $user_data = $stmt->fetchAll(PDO::PARAM_STR);
}

//ログイン認証
//入力されたアドレス、パスワードの配列がDB内にあれば変数judgeにtrueなければfalseを格納
//ログイン成功時、ＤＢ内のＩＤをセッション情報に保存
// var_dump($user_data);
// exit();

if (in_array($email,array_column( $user_data, 'email')) && in_array($password,array_column( $user_data, 'userpassword'))){
    //セッション情報保存
    $_SESSION['uid'] = $user_data[array_search($email,array_column( $user_data, 'email'))]['id'];
    $judge = 'true';
    // var_dump($_SESSION['uid']);
    // exit();
} else {
    $judge = 'false';
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/signin.css">
    <title></title>
</head>

<body>
    <div class='loader'>
        <p>Loading...</p>
    </div>
    <main>
        <form action="signin_data.php" method="POST">
            <div class="signin">
                <fieldset class="signin-content">
                    <input type="text" name="email" id='mail' placeholder="email">
                    <input type="password" name="pass" id='password' placeholder="password">
                    <button id="signupbtn">sign-in</button>
                </fieldset>
            </div>
        </form>
    </main>
</body>
<script>
    //ログイン判定
    //phpから受け取った変数judgeがtureであればサインイン成功
    const judge = <?= $judge ?>;
    console.log(judge);
    if (judge) {
        alert('サインイン成功')
        window.location.href = "../main.php";
    } else {
        alert('サインインできません')
        window.location.href = '../index.php';
    }
</script>

</html>