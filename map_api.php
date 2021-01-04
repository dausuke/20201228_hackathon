<?php
session_start();
ini_set('display_errors', 1);

//ユーザー情報取得
$uid = $_SESSION["uid"];
$shopname = $_POST['name'];
$area = $_POST['area'];
// var_dump($_POST);
// exit();

//googlemapAPI
//入力された店名、エリアから店舗情報取得
function searchPlace($keyword) {
	$api_key = "";
	$keyword = urlencode($keyword);
	$url = "https://maps.googleapis.com/maps/api/place/textsearch/json?v=3.33&language=ja&region=JP&key={$api_key}&query={$keyword}";
	$json = file_get_contents($url);
	return json_decode($json, true);
}
$responsData = searchPlace("{$shopname} {$area}");
// var_dump($responsData);
// exit();
$place_data = array();
foreach($responsData['results'] as $key => $value){
    $place_data = array_merge($place_data,array($key=>$value['name']));
}
echo json_encode($place_data,JSON_UNESCAPED_UNICODE);
$_SESSION['mapdata'] = $responsData['results'];