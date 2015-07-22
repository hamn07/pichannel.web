<?php
require_once 'API.class.php';
require_once '../PichannelDatabase.class.php';

class MyAPI extends API
{
    protected $User;
    public function __construct($request, $origin) {
        parent::__construct($request);

        // Abstracted out for example
        $APIKey = new APIKey();
        $User = new User();
        if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            throw new Exception('Invalid API Key');
        } else if (array_key_exists('token', $this->request) &&
             !$User->get('token', $this->request['token'])) {

            throw new Exception('Invalid User Token');
        }

        $this->User = $User;
    }

    /**
     * Example of an Endpoint
     */
     protected function user() {
     	switch ($this->method) {
	        case 'DELETE':
	            break;
	        case 'POST':
	            break;
	        case 'GET':
	        	$obj_db = new PichannelDatabase();
	        	return $obj_db->queryPosts($this->verb);
	        	$obj_db = null;
// 	        	return "Your name is " . $this->verb;
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
 }
class User {
  public $name = 'hamn07';

  public function get(){
    return true;
  }
}
class APIKey {
  public function verifyKey($apikey, $origin_host) {
    return true;
  }
}
