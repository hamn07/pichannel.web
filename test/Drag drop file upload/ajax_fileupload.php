<?php
  $str = file_get_contents('php://input');
  echo $filename = md5(time().uniqid()).".jpg";
  file_put_contents("uploads/".$filename,$str);
  // In demo version i delete uplaoded file immideately, Please remove it later
  //unlink("uploads/".$filename);
?>