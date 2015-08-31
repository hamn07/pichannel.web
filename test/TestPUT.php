<?php
$ch = curl_init();

$put = http_build_query(array(
		"apiKey"=>"key1",
		"flag"=>"0",
));

$options = array(
		CURLOPT_URL=> "localhost/api/user/hamn07/subscription/hamn07",
// 		CURLOPT_URL=> "localhost/test/ReceivePUT.php",		
        CURLOPT_CUSTOMREQUEST=> "PUT",
        CURLOPT_POSTFIELDS=> $put,
        CURLOPT_RETURNTRANSFER=> true,
);

curl_setopt_array($ch, $options);

$s_response = curl_exec($ch);

echo $s_response;

curl_close($ch);