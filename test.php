<?php
// session_start();
// $uid = $_SESSION["uid"];
// ini_set('display_errors', 1);

// // $up_file  = "";
// // $up_dri = "/Users/daisuke/Desktop/img/icon_img/";
// // $up_ok = false;
// // $tmp_file = isset($_FILES["image"]["tmp_name"]) ? $_FILES["image"]["tmp_name"] : "";
// // $org_file = isset($_FILES["image"]["name"])     ? $_FILES["image"]["name"]     : "";

// // if( $tmp_file != "" && is_uploaded_file($tmp_file) ){
// //     $split = explode('.', $org_file); $ext = end($split);
// //     if( $ext != "" && $ext != $org_file  ){

// //         $up_file = date("Ymd_His.") . mt_rand(1000,9999) . ".$ext";
// //         // var_dump($up_file);
// //         // exit();
// //         $up_ok = move_uploaded_file( $tmp_file, $up_dri.$up_file);
// //         chmod($up_dri,0666);
// //     }
// // }
// // var_dump($tmp_file);
// // var_dump($up_file);
// // var_dump($up_dri.$up_file);
// // var_dump($up_ok);

// // 画像データ
// // var_dump($_FILES['image']);
// // exit();

// $img_data = file_get_contents($_FILES['image']['tmp_name']);

// // DB接続の設定
// include('functions.php');
// $pdo = connect_to_db();

// $sql = 'UPDATE userdata_table SET icon=:img_data WHERE id=:userid';
// $stmt = $pdo->prepare($sql);

// $stmt->bindValue(':img_data', $img_data, PDO::PARAM_STR);
// $stmt->bindValue(':userid', $uid, PDO::PARAM_STR);

// $status = $stmt->execute();



// $read_sql = 'SELECT icon FROM userdata_table';
// $read_stmt = $pdo->prepare($read_sql);
// //SQL実行
// $read_status = $read_stmt->execute();

// if ($read_status == false) {
//     $error = $read_stmt->errorInfo();
//     // データ登録失敗次にエラーを表示
//     exit('sqlError:' . $error[2]);
// } else {
//     // データ表示
//     $result = $read_stmt->fetchAll(PDO::PARAM_STR);
//     $imgPass = base64_encode($result[0]['icon']);
//     // echo '<img src="data:image/jpeg;base64,'. $imgPass. '>';

//     echo $imgPass;
//     exit();

// }

?>
