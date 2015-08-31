<?
  $s_device = isset($_GET['device'])?$_GET['device']:"no device";
  $s_data   = isset($_GET['data'])?$_GET['data']:"no data";
  date_default_timezone_set('Asia/Taipei');
  $s_datetime = date('m/d/Y h:i:s a', time());
  file_put_contents ("Tail-Log.log","$s_datetime [$s_device] $s_data\n",FILE_APPEND);
  echo 'OK';
