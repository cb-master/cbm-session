<?php
/**
 * Project: Laika MVC Framework
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Session;

use CBM\Model\Model;
use CBM\SessionHelper\SessionException;

class Handler
{
	// Session Name
	public static String $name = 'laika';

	// Secure Session
	public static Bool $secure = true;

	// HTTP Secure Session
	public static Bool $http = true;

	// Session Path
	public static String $path = '/';



    // DB ID
    private $id = "id";

    // Table Name Var
    private $table = "sessions";

    // Data Var
    private $session = "session_data";

    // Last Update
    private $access = "last_access";

	// Table Exist
	private static Bool $exist = false;

	// Session in Database
	private static Bool $session_in_db = true;

	// Session Instance
	private static null|object $instance = null;

	// Set Session in Database
	/**
	 * @param bool $bool - Default is true. Use false To Store Session in system tmp folder.
	 */
	public static function session_in_db(bool $bool = true):void
	{
		self::$session_in_db = $bool;
	}

	// Load Instance
	protected static function instance()
	{
		return self::$instance ?: new Static;
	}


	// Start Session
    protected function start()
	{
		// Check Database Model Exist
		try {
			if(!class_exists(Model::class)){
				throw new SessionException("'CBM\Model\Model' Class Does Not Exist", 50000);
			}
		} catch (SessionException $e) {
			echo $e->message();
		}

        // Start Session
		if(session_status() !== PHP_SESSION_ACTIVE)
		{
			if(self::$session_in_db){
				// Create Table if Not Exist
				if(!self::$exist){
					$this->session_table_exist();
				}

				session_set_save_handler(
					array($this, "open"),
					array($this, "close"),
					array($this, "read"),
					array($this, "write"),
					array($this, "destroy"),
					array($this, "gc")
				);
				// register_shutdown_function("session_write_close");
			}

			// Set Session Name
            session_name(self::$name);
            ini_set("session.use_only_cookies",true);
            ini_set("session.use_strict_mode",true);
			ini_set('session.gc_probability', 1);
			ini_set('session.gc_divisor', 100);
            ini_set("session.gc_maxlifetime",strtotime("+5 minutes") - time());
            // Set Parameters
            session_set_cookie_params([
                "path"      =>  self::$path,
                "secure"    =>  self::$secure,
                "httponly"  =>  self::$http,
                "samesite"  =>  "strict"
            ]);

            // Start Session
            session_start();
        }
	}

	// Open DB Connection
	public function open():bool
	{
		return true;
	}

	// Close DB Connection
	public function close():bool
	{
		return true;
	}

	// Read DB Data
	public function read($id):string
	{
		$dbData = Model::table($this->table)->select()->where([$this->id => $id])->single();
		$data = is_array($dbData) ? $dbData : self::toArray($dbData);
		return $data[$this->session] ?? '';
	}

	// Insert DB Data
	public function write($id, $data):bool
	{
		// Create time stamp
		$access = time();
		// Insert/Update Data
		$array = [
			$this->id       =>  $id,
			$this->access   =>  $access,
			$this->session  =>  $data
		];

		return Model::table($this->table)->replace($array) ? true : false;
	}

	// Destroy DB Data
	public function destroy($id):bool
	{
		return Model::table($this->table)->where([$this->id => $id])->pop() ? true : false;
	}

	// Garbage Collection
	public function gc($max):bool
	{
		return Model::table($this->table)->where([$this->access => (time() - $max)], '<')->pop() ? true : false;
	}

	// Create Table if Not Exist
	private function session_table_exist()
	{
		if(!Model::table($this->table)->exist()){
			$this->create_table();
		}
		self::$exist = true;
	}

	// Create Session Table
	private function create_table():void
	{
		Model::table($this->table)->column($this->id, 'varchar(50)')
						->column($this->access, 'int(12)')
						->column($this->session, 'longtext')
						->primary($this->id)
						->index($this->access)
						->create();
	}

	// Object To Array
	/**
	 * @param array|object $obj - Required Argument
	 */
	private function toArray(array|object $obj):array
	{
		return json_decode(json_encode($obj), true);
	}
}