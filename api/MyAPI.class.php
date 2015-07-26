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
	        case 'DELETE':
	            break;
	        case 'POST':
	        	return $this->createNewPost();
	        	
	            break;
	        case 'GET':
	        	return $this->obj_db->queryPosts($this->User->getUserID());
	            break;
	        case 'PUT':
	            break;
	        default:
	            break;
     	}
     	
     	
//         if ($this->method == 'GET') {
        	
//             return "Your name is " . $this->verb;
//         } else {
//             return "Only accepts GET requests";
//         }
     }
     
     private function createNewPost() {
     	
     	$s_file_extention = ".jpg";
     	// 取得圖片內容
     	$s_file_contents = $_POST['s_file_contents'];
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
     	        	
     	// 取得拍攝時間
     	$arr_exif_data = @exif_read_data($s_filepath);
     	$s_exif_unixtimestamp_original = $arr_exif_data?strtotime($arr_exif_data['DateTimeOriginal']):null;
     	// 存進DB
//      	$obj_db = new PichannelDatabase();
     	$this->obj_db->insertImage($s_file_contents_sha1,$s_exif_unixtimestamp_original);
     	$this->obj_db->insertPost(time(),$this->User->getUserID(),$s_file_contents_sha1);

     	
     	// 回傳 json物件{id,url}
//      	header("Content-Type: application/json", true);
     	$s_urlpath = $this->s_host_domain . $s_dir . "/" . $s_filename . $s_file_extention;
     	return $s_urlpath;
//      	return "{\"url\": \"$s_urlpath\"}";
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
