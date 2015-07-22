<?php
require 'PichannelDatabase.class.php';
session_start();
  // 取得圖片內容
  $s_file_extention = ".jpg";
  $s_user_id = $_SESSION['user_id'];
  $s_file_contents = file_get_contents('php://input');
  // 使用雜湊演算法sha1依圖片內容產生unique key
  $s_file_contents_sha1 = sha1($s_file_contents);
  // 取前兩碼為目錄名，避免過多圖片存在同一目錄造成存取效能問題
  $s_dir = "img-repo/" . substr($s_file_contents_sha1,0,2);
  if (!is_dir($s_dir)) {
    mkdir($s_dir);
  }
  // 圖片儲存，檔名為第2~第40碼
  $s_filename = substr($s_file_contents_sha1,2);
  $s_filepath = $s_dir . "/" . $s_filename . $s_file_extention;
  if (!file_exists($s_filepath)){
    file_put_contents($s_filepath, $s_file_contents);
  }

  // 回傳 json物件{id,url}
  header("Content-Type: application/json", true);
  echo "{\"id\": \"{$_GET['rand']}\", \"url\": \"$s_filepath\"}";
  // 取得拍攝時間
  $arr_exif_data = @exif_read_data($s_filepath);
  $s_exif_unixtimestamp_original = $arr_exif_data?strtotime($arr_exif_data['DateTimeOriginal']):null;
  // 存進DB
  $obj_db = new PichannelDatabase();
  $obj_db->insertImage($s_file_contents_sha1,$s_exif_unixtimestamp_original);
  $obj_db->insertPost(time(),$s_user_id,$s_file_contents_sha1);
  $obj_db = null;
