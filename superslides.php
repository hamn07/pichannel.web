<?php
session_start();

// 取得domain_name
$s_api_domain_name = parse_ini_file("conf.ini")['api_domain_name'];

$s_user_id = isset($_GET['user'])?$_GET['user']:"hamn07";
$_SESSION["user_id"]=$s_user_id;


$ch = curl_init();
$options = array(
		CURLOPT_URL=>$s_api_domain_name . "/api/user/" . $s_user_id . "?apiKey=key1&max-result=10",
		CURLOPT_RETURNTRANSFER=>true,
);
curl_setopt_array($ch, $options);

// 使用array_reverse將最新上傳的圖片排列在最前面
$arr_posts = json_decode( curl_exec($ch) , true);
if ($arr_posts) {
	$arr_posts = array_reverse($arr_posts);
}


curl_close($ch);

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Superslides - A fullscreen slider for jQuery</title>
  <link rel="stylesheet" href="bower_components/superslides/dist/stylesheets/superslides.css">
</head>
<body>
  <div id="slides">
    <div class="slides-container">
      <img src="http://localhost/img-repo/c4/14d06c364003654c8a820f43a92243793e52a8.jpg" alt="Cinelli">
      <img src="http://localhost/img-repo/4c/6499dbf70194a510a5995f927f0212aa691627.jpg" width="1024" height="682" alt="Surly">
      <img src="http://localhost/img-repo/b6/e24c615964d00718daecd060a1054a5c6e6bbd.jpg" width="1024" height="683" alt="Cinelli">
      <img src="http://localhost/img-repo/fa/bc53e2d828fe0aae17c4a58143818e4e0b331a.jpg" width="1024" height="685" alt="Affinity">
    </div>
  </div>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="bower_components/superslides/examples/javascripts/jquery.easing.1.3.js"></script>
  <script src="bower_components/superslides/examples/javascripts/jquery.animate-enhanced.min.js"></script>
  <script src="bower_components/superslides/dist/jquery.superslides.js" type="text/javascript" charset="utf-8"></script>
  <script>
    $(function() {
      $('#slides').superslides({
        hashchange: true,
        play: 8000
      });

      $('#slides').on('mouseenter', function() {
        $(this).superslides('stop');
        console.log('Stopped')
      });
      $('#slides').on('mouseleave', function() {
        $(this).superslides('start');
        console.log('Started')
      });
    });
  </script>
</body>
</html>
