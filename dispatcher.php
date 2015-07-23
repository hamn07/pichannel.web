<?php
session_start();
  // 取得圖片內容
  $s_file_contents = file_get_contents('php://input');
  $ch = curl_init();
  $post = array(
  		"s_file_contents"=>$s_file_contents,
  );
  $options = array(
  		CURLOPT_URL=> 'http://localhost/reciever.php',
//   		CURLOPT_URL=> './reciever.php',
  		CURLOPT_POST=> true,
  		CURLOPT_POSTFIELDS=> $post,
  		CURLOPT_RETURNTRANSFER=> true,
  );
  curl_setopt_array($ch, $options);
  var_dump(curl_exec($ch));
  curl_close($ch);