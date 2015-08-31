<?php
session_start();
set_time_limit(90);

if (isset($_SESSION['user_id'])) {
	
	$s_user_id = $_SESSION['user_id'];
	
} else {
	// go to login page
    $s_user_id = 'hamn07';
}

$s_api_domain_name = parse_ini_file("conf.ini")['api_domain_name'];

switch ($_SERVER['REQUEST_METHOD']) {
	
	// 上傳(發佈)圖片post
	case "POST":
		// 取得圖片內容
		$s_file_contents = file_get_contents('php://input');
		if ($s_file_contents=="") {
			$s_file_contents = file_get_contents($_FILES['picture']['tmp_name']);
		}

		// 使用curl上傳圖片
		$ch = curl_init();
		$post = array(
				"apiKey"=>"key1",
				"s_file_contents"=>base64_encode($s_file_contents),
		);
		$options = array(
				CURLOPT_URL=> "$s_api_domain_name/api/user/$s_user_id",
				CURLOPT_POST=> true,
				CURLOPT_POSTFIELDS=> $post,
				CURLOPT_RETURNTRANSFER=> true,
				
		);
		curl_setopt_array($ch, $options);
		
		$responseFromServer = json_decode(curl_exec($ch),true);
		
		header("Content-Type: application/json", true);

		$responseToClient = json_encode(array(
			"id"=>$_GET['rand'],
			"url"=>$responseFromServer["url"],
			"postId"=>$responseFromServer["lastInsertId"],
		));
		echo $responseToClient;
		curl_close($ch);
		
		break;
		
	// 修改說明文字
	case "PUT":
		
		// php沒有提供$_PUT，所以預處理傳過來的資料，將之解到$_PUT
		parse_str(file_get_contents("php://input"), $_PUT);
		foreach ($_PUT as $key => $value)
		{
			unset($_PUT[$key]);
		
			$_PUT[str_replace('amp;', '', $key)] = $value;
		}
		
		// 使用curl來更新說明文字
		$ch = curl_init();
		
		$put = http_build_query(array(
				"apiKey"=>"key1",
				"text"=>$_PUT['text'],
		));
		
		$options = array(
				CURLOPT_URL=> "$s_api_domain_name/api/user/$s_user_id/post/{$_PUT['postId']}",
// 				CURLOPT_PUT=> true,
// 				CURLOPT_INFILE=> NULL,
// 				CURLOPT_INFILESIZE=> 0,
				CURLOPT_CUSTOMREQUEST=> "PUT",
				CURLOPT_POSTFIELDS=> $put,
				CURLOPT_RETURNTRANSFER=> true,
		);
		
		curl_setopt_array($ch, $options);
		
		$s_response = curl_exec($ch);
		
		echo $s_response;
		
		curl_close($ch);
		
		break;
		
		
	// 刪除圖片post
	case "DELETE":
		// php沒有提供$_PUT，所以預處理傳過來的資料，將之解到$_PUT
		parse_str(file_get_contents("php://input"), $_DELETE);
		foreach ($_DELETE as $key => $value)
		{
			unset($_DELETE[$key]);
		
			$_DELETE[str_replace('amp;', '', $key)] = $value;
		}
		
		// 使用curl來刪除圖片post
		$ch = curl_init();
		
		$delete = http_build_query(array(
				"apiKey"=>"key1",
		));
		
		$options = array(
				CURLOPT_URL=> "$s_api_domain_name/api/user/$s_user_id/post/{$_DELETE['postId']}",
				CURLOPT_CUSTOMREQUEST=> "DELETE",
				CURLOPT_POSTFIELDS=> $delete,
				CURLOPT_RETURNTRANSFER=> true,
		);
		
		curl_setopt_array($ch, $options);
		
		$s_response = curl_exec($ch);
		
		echo $s_response;
		
		curl_close($ch);
		
		break;
	
	default:
		break;
}



