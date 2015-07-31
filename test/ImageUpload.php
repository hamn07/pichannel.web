<?php

if (isset($_FILES['upload'])) {
	$upload = $_FILES['upload'];

	if ($upload['error']==0) {
		$srcimg = imagecreatefromjpeg($upload['tmp_name']);
		$dstimg = imagecreate(200,200);

		$srcw = imagesx($srcimg);
		$srch = imagesy($srcimg);

		if ($srcw > $srch) {
			$dstw = 200;
			$dsth = $srch / $srcw * 200;
		} else {
			$dstw = $srcw / $srch * 200;
			$dsth = 200;
		}

		imagecopyresized($dstimg,$srcimg,0,0,0,0,$dstw,$dsth,$srcw,$srch);


		// $red = imagecolorallocate($srcimg,255,0,0);
		// imagefilledrectangle($srcimg,10,10,40,40,$red);


		imagejpeg($srcimg, './ImageUpload.jpg');
		imagejpeg($dstimg, './ImageUpload_resized.jpg');

		imagedestroy($srcimg);
		imagedestroy($dstimg);

	}

}

?>

<form method="post" enctype="multipart/form-data">
	<input type="file" name="upload">
	<input type='submit' value='upload'>
</form>
