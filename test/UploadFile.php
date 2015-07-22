<?php
  echo '<pre>';
  var_dump($_FILES['files']);
  echo "</pre>";
  if (isset($_FILES['files'])){
  for ($i=0; $i <count($_FILES['files']['name']) ; $i++) {
    # code...
    if($_FILES['files']['error'][$i]==0){
      if(move_uploaded_file($_FILES["files"]["tmp_name"][$i], "./".$_FILES["files"]["name"][$i])){
    		echo $_FILES["files"]["name"][$i]."上傳成功!<br />";
    	}else{
				echo $_FILES["files"]["name"][$i]."上傳失敗!<br />";
			}
    }
  }
}
	// $i=count($_FILES["fileUpload"]["name"]);
	// for ($j=0;$j<$i;$j++){
	// 	if($_FILES["fileUpload"]["error"][$j]==0){
	// 		if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"][$j], "./".$_FILES["fileUpload"]["name"][$j])){
	// 			echo $_FILES["fileUpload"]["name"][$j]."上傳成功!<br />";
	// 		}else{
	// 			echo $_FILES["fileUpload"]["name"][$j]."上傳失敗!<br />";
	// 		}
	// 	}
	// }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>上傳檔案表單</title>
  </head>
  <body>
    <form action="" method="post" enctype="multipart/form-data">
    請選取要上傳的檔案：<br />
    檔案<input type="file" multiple accept="image/*" name="files[]" /><br />
    <input type="submit" value="送出資料" />
    </form>
  </body>
</html>
