<?php
require_once 'API.class.php';
require_once 'PichannelDatabase.class.php';

class MyAPI extends API
{
    protected $User;
    private $obj_db;
    private $s_host_domain;

    public function __construct($request, $origin) {
        parent::__construct($request);

        // Abstracted out for example
        $APIKey = new APIKey();
        $User = new User($this->verb);
        if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            throw new Exception('Invalid API Key');
        } else if (array_key_exists('token', $this->request) &&
             !$User->get('token', $this->request['token'])) {

            throw new Exception('Invalid User Token');
        }

        $this->User = $User;
        $this->obj_db = new PichannelDatabase();

        $this->s_host_domain = $this->obj_db->getDomainName();
    }

    /**
     * Example of an Endpoint
     */
     protected function user($args) {
     	switch ($this->method) {
     		
     		// 刪除圖片post
	        case 'DELETE':
	        	return $this->obj_db->deletePost($args[1], $this->User->getUserID());
	            break;
	        
	        // 上傳圖片post
	        case 'POST':
	        	return $this->createNewPost();
	            break;
	        
	        case 'GET':
	        	  switch  (isset($args[0])?$args[0]:"") {
	        	  	case 'subscription':
	        	  		return $this->obj_db->checkNewPostsFlag($this->User->getUserID(), $args[1]);
	        	  		break;
	        	  	case 'camera':
	        	  		return $this->obj_db->getLastestPost($this->User->getUserID());
	        	  		break;
	        	    // 取得此user所有post
	        	  	default:
	        	  		return $this->obj_db->queryPosts($this->User->getUserID());
	        	  		break;
	        	  }
	        	  
	        
	        case 'PUT':
	        	  switch (isset($args[0])?$args[0]:"") {
	        	  	case 'subscription':
//                     return $this->obj_db->updateNewPostsFlag($this->User->getUserID(), 0);
					return $this->obj_db->updateNewPostsFlag($this->User->getUserID(), $args[1] , $this->request['flag']);
	        	  		break;
	        	  	// 修改說明文字
	        	  	default:
	        	  		return $this->obj_db->updatePostText($args[1], $this->User->getUserID(), $this->request["text"]);
	        	  		break;
	        	  }
	        	  
	        default:
	            break;
     	}
     }

     private function createNewPost() {

     	$s_file_extention = ".jpg";
     	// 取得圖片內容
     	$s_file_contents = base64_decode($_POST['s_file_contents']);
     	// 使用雜湊演算法sha1依圖片內容產生unique key
     	$s_file_contents_sha1 = sha1($s_file_contents);
     	// 取前兩碼為目錄名，避免過多圖片存在同一目錄造成存取效能問題
     	$s_dir = "/img-repo/" . substr($s_file_contents_sha1,0,2);
     	if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $s_dir)) {
     		mkdir($_SERVER['DOCUMENT_ROOT'] . $s_dir);
     	}
     	// 圖片儲存，檔名為第2~第40碼
     	$s_filename = substr($s_file_contents_sha1,2);
     	$s_filepath = $_SERVER['DOCUMENT_ROOT'] . $s_dir . "/" . $s_filename . $s_file_extention;

     	if (!file_exists($s_filepath)){
     		file_put_contents($s_filepath, $s_file_contents);
     	}

     	// 通知subscription有新相片 
     	// note: 不明原因於insert post image之後會無法作用，因此放置於此
     	$this->obj_db->updateNewPostsFlag($this->User->getUserID(), 1);
     	
     	// 取得拍攝時間
     	$arr_exif_data = @exif_read_data($s_filepath);
     	$s_exif_unixtimestamp_original = $arr_exif_data?strtotime(isset($arr_exif_data['DateTimeOriginal'])?$arr_exif_data['DateTimeOriginal']:""):null;

     	// 存進DB
      	$this->obj_db->insertImage($s_file_contents_sha1,$s_exif_unixtimestamp_original);
     	$lastInsertId = $this->obj_db->insertPost(time(),$this->User->getUserID(),$s_file_contents_sha1);
		
     	// 通知subscription有新相片
//      	$this->obj_db->updateNewPostsFlag($this->User->getUserID(), 1);
     	
     	
     	header("Content-Type: application/json", true);
     	$s_urlpath = $this->s_host_domain . $s_dir . "/" . $s_filename . $s_file_extention;
     	
     	// 回傳array物件，$this->_response會將之json_encode再送出
     	return array(
     		"url"=>$s_urlpath,
     		"lastInsertId"=>$lastInsertId,
     	);
     	

//      	return $s_urlpath . "," . $lastInsertId;
//      	return '{"url": "' . $s_urlpath . '", "data-post-id":"' . $lastInsertId . '"}';
     }

     function __destruct() {
     	$this->obj_db = null;
     }
}

/*
 * User
 */
class User {
  private $s_user_id;

  function __construct($user_id) {
  	$this->s_user_id = $user_id;
  }

  function getUserID() {
  	return $this->s_user_id;
  }

  public function get(){
    return true;
  }
}


/*
 *
 */
class APIKey {
  public function verifyKey($apikey, $origin_host) {
    return true;
  }
}
