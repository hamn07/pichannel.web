<?php
class PichannelDatabase {
    private $db;
// 	private $s_domain_name = "http://ec2-52-26-138-212.us-west-2.compute.amazonaws.com";
	private $s_domain_name;
    function  __construct(){
      // 取得domain_name
      $this->s_domain_name = parse_ini_file("conf.ini")['host_domain_name'];


      // 建立database connection of the server
      $this->db = new PDO("mysql:host=localhost;dbname=pichannel;charset=utf8;port=3306", "root", "root");
      // $db->exec("set names utf8");

      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // ref : http://stackoverflow.com/questions/17059362/why-should-use-attr-emulate-prepares-is-any-alternatives-of-mysql-real-escape-s
      // 安全性設定，不使用模擬，使用原生PDO，防止PDO針對沒支援prepare statemet的DB來進行模擬，使用不支援prepare statement的MySQL版本才需要開啟
      $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }


    // 新增照片資訊(檔名、拍攝時間)
    function insertImage($sha1,$timestamp){

      $sql = <<<sqlText
        INSERT INTO image VALUES (:sha1,:timestamp)
sqlText;

      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':sha1',$sha1);
      $stmt->bindValue(':timestamp',$timestamp);

      try {
        $num = $stmt->execute();

      } catch (PDOException $ex) {
        switch ($ex->getCode()){
          //duplicate key in table
          case 23000:
            $num = 0;
        }
      }
      $stmt = null;
      return $num;
    }



    // 新增post
    function insertPost($timestamp,$user_id,$image_sha1){
      $sql = <<<sqlText
      INSERT INTO post(
        post_unixtimestamp_original,
        user_id,
        image_sha1
      )
      VALUES (
        :timestamp,
        :user_id,
        :image_sha1
      )
sqlText;
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':image_sha1',$image_sha1);
      $stmt->bindValue(':timestamp',$timestamp);
      $stmt->bindValue(':user_id',$user_id);

      try {
        $num = $stmt->execute();

      } catch (PDOException $ex) {
        switch ($ex->getCode()){
          //duplicate key in table
          case 23000:
            $num = 0;
        }
      }
      $stmt = null;

      return $this->db->lastInsertId();
    }



    // 修改說明文字
    function updatePostText($postId,$userId,$text){
      $sql = <<<sqlText
      UPDATE post
         SET text = :text
       WHERE id = :postId
         AND user_id = :userId
sqlText;

      $result = $text;
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':text',$text);
      $stmt->bindValue(':postId',$postId);
      $stmt->bindValue(':userId',$userId);

      try {
        $num = $stmt->execute();

      } catch (PDOException $ex) {

      	$result = $ex->getMessage();

      }
      $stmt = null;
      return $result;
    }

    // 刪除上傳圖片post
    function deletePost($postId,$userId){
      $sql = <<<sqlText
      DELETE FROM post
       WHERE id = :postId
         AND user_id = :userId
sqlText;

      $result = "success";
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':postId',$postId);
      $stmt->bindValue(':userId',$userId);

      try {
      	$num = $stmt->execute();

      } catch (PDOException $ex) {

      	$result = $ex->getMessage();

      }
      $stmt = null;
      return $result;
    }

    function updateMusic($timestamp,$user_id){

    }
	function getLastestPost($user_id){
		$sql = <<<sqlText
      SELECT CONCAT("$this->s_domain_name","/img-repo/",SUBSTR(image_sha1,1,2),"/",SUBSTR(image_sha1,3),".jpg") image_src
        FROM post
       WHERE user_id = :user_id
       ORDER BY post_unixtimestamp_original DESC;
sqlText;
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':user_id',$user_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt = null;
		
		
		return $row;
	}
    // 取得所有的posts
    function queryPosts($user_id){
      $sql = <<<sqlText
      SELECT FROM_UNIXTIME(post_unixtimestamp_original) post_time,
             CONCAT("$this->s_domain_name","/img-repo/",SUBSTR(image_sha1,1,2),"/",SUBSTR(image_sha1,3),".jpg") image_src,
             text,
             id
        FROM post
       WHERE user_id = :user_id
       ORDER BY post_unixtimestamp_original DESC;
sqlText;

	  
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':user_id',$user_id);
      $stmt->execute(array($user_id));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = null;


      return $rows;
    }
    function checkNewPostsFlag($user_id,$subscription) {
      $sql = <<<sqlText
      SELECT CASE has_new_posts
      		   WHEN 0 THEN 'false'
      		   ELSE 'true'
      		 END AS has_new_posts
      	FROM subscription
       WHERE user_id = :user_id
         AND subscription = :subscription
sqlText;
      
      $stmt = $this->db->prepare($sql);
      $stmt->bindValue(':user_id',$user_id);
      $stmt->bindValue(':subscription',$subscription);
      $stmt->execute();
      $row  = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = null;
      return $row;
    }
    function updateNewPostsFlag() {
    	
    	  switch (func_num_args()) {
    	  	case 2:
    	  		$sql = <<<sqlText
      UPDATE subscription
    	     SET has_new_posts = :flag
       WHERE subscription = :subscription
sqlText;
    	  		$subscription = func_get_arg(0);
    	  		$flag         = func_get_arg(1);
    	  		$result = "success";
    	  		$stmt = $this->db->prepare($sql);
    	  		$stmt->bindValue(':subscription',$subscription);
    	  		$stmt->bindValue(':flag', $flag, PDO::PARAM_INT);
    	  		break;
    	  		
    	  	case 3:
    	  		$sql = <<<sqlText
      UPDATE subscription
    	     SET has_new_posts = :flag
       WHERE user_id = :user_id
         AND subscription = :subscription
sqlText;
    	  		$user_id = func_get_arg(0);
    	  		$subscription = func_get_arg(1);
    	  		$flag = func_get_arg(2);
    	  		$result = "success";
    	  		$stmt = $this->db->prepare($sql);
    	  		$stmt->bindValue(':user_id',$user_id);
    	  		$stmt->bindValue(':subscription',$subscription);
    	  		$stmt->bindValue(':flag', $flag, PDO::PARAM_INT);
    	  			
    	  		break;
    	  	default:
    	  		return;
    	  }
    	
    	  
    	  
    	  
    	  
 
    	  try {
    	  	$num = $stmt->execute();
    	  } catch (PDOException $ex) {
    	  
    	  	$result = $ex->getMessage();
    	  
    	  }
    	  $stmt = null;
    	  return $result;
    	
    	
    }

    function queryMusicList(){

    }

    function getDomainName() {
    	return $this->s_domain_name;
    }

    function __destruct(){
      $db = null;
    }
}
