<?php
session_start();
set_time_limit(90);
  
$s_api_domain_name = parse_ini_file("conf.ini")['api_domain_name'];

// 取得圖片內容
  $s_file_contents = file_get_contents('php://input');
  $ch = curl_init();
  $post = array(
  		"apiKey"=>"key1",
  		"s_file_contents"=>$s_file_contents,
  );
  $options = array(
//   		CURLOPT_URL=> 'http://localhost/reciever.php',
  		CURLOPT_URL=> $s_api_domain_name . '/api/user/hamn07',
  		CURLOPT_POST=> true,
  		CURLOPT_POSTFIELDS=> $post,
  		CURLOPT_RETURNTRANSFER=> true,
  );
  curl_setopt_array($ch, $options); 
  
  $s_urlpath = curl_exec($ch);
//   echo $s_urlpath;
  header("Content-Type: application/json", true);
  echo "{\"id\": \"{$_GET['rand']}\", \"url\": $s_urlpath}";
  curl_close($ch);