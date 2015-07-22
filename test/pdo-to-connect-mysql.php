<?php
require_once '../PichannelDatabase.class.php';

header("Content-Type: application/json", true);
// header("Content-Type: text/html", true);

echo "<pre>";
// $obj_db = new PichannelDatabase();

// $num = $obj_db->insertImage("2cbb218e3906d890583d61b90fa5b8b5a9307c32","1334567890");
// $num = $obj_db->insertImage("2cbb218e3906d890383d61b90fa5b8b5a9307c3n",null);
// $num = $obj_db->insertPost("1234567890","hamn07","2cbb218e3906d890383d61b90fa5b8b5a9307c3n");
// $num = $obj_db->insertPost("1234567890","wheel11","2cbb218e3906d890383d61b90fa5b8b5a9307c3n");
// echo $num . " inserted.<br>";

// var_dump(json_encode($obj_db->queryPosts("hamn07")));

// $obj_db = null;

$ch = curl_init();
$url = "http://localhost/api/user/wheel11?apiKey=key1&max-result=10";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result=curl_exec($ch);
curl_close($ch);
// var_dump($result);
var_dump(json_decode($result,true));



echo "</pre>";