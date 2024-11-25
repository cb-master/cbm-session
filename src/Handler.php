<?php
/**
 * APP Name:        Laika Session Model
 * APP Provider:    Showket Ahmed
 * APP Link:        https://cloudbillmaster.com
 * APP Contact:     riyadtayf@gmail.com
 * APP Version:     1.0.0
 * APP Company:     Cloud Bill Master Ltd.
 */

// Namespace
namespace CBM\Session;

use CBM\Model\Model;
use CBM\SessionHelper\SessionException;

class Handler
{
    // DB ID
    public $id = "session_id";

    // Table Name Var
    public $table = "session";

    // Data Var
    private $session = "session_data";

    // Last Update
    private $access = "last_access";

	// Session Name
	public static String $name = 'laika';

	// Secure Session
	public static Bool $secure = true;

	// HTTP Secure Session
	public static Bool $http = true;

	// Session Path
	public static String $path = '/';

	// Table Exist
	private static Bool $exist = false;

    public function begin()
	{
		// Check Database Model Exist
		try {
			if(!class_exists('Model')){
				throw new SessionException("'CBM\Model\Model' Class Does Not Exist", 50000);
			}
		} catch (SessionException $e) {
			echo $e->message();
		}
		// Create Table if Not Exist
		if(!self::$exist){
			$this->session_table_exist();
		}

        // Start Session
		if(session_status() !== PHP_SESSION_ACTIVE)
		{
			session_set_save_handler(
				array($this, "_open"),
				array($this, "_close"),
				array($this, "_read"),
				array($this, "_write"),
				array($this, "_destroy"),
				array($this, "_gc")
			);
			register_shutdown_function("session_write_close");

			// Set Session Name
            session_name(self::$name);
            ini_set("session.use_only_cookies",true);
            ini_set("session.use_strict_mode",true);
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
	public function _open($path, $name):bool
	{
		return true;
	}

	// Close DB Connection
	public function _close():bool
	{
		return true;
	}

	// Read DB Data
	public function _read($id):string
	{
		$data = $this->to_array(Model::conn()->table($this->table)->select()->where([$this->id => $id])->single());
		return $data[$this->session] ?? '';
	}

	// Insert DB Data
	public function _write($id, $data):bool
	{
		// Create time stamp
		$access = time();
		// Insert/Update Data
		$array = [
			$this->id       =>  $id,
			$this->access   =>  $access,
			$this->session  =>  $data
		];

		return Model::conn()->table($this->table)->replace($array) ? true : false;
	}

	// Destroy DB Data
	public function _destroy($id):bool
	{
		return Model::conn()->table($this->table)->where([$this->id => $id])->pop() ? true : false;
	}

	// Garbage Collection
	public function _gc($max):bool
	{
		return Model::conn()->table($this->table)->where([$this->access => (time() - $max)], '<')->pop() ? true : false;
	}

	// Create Table if Not Exist
	private function session_table_exist()
	{
		if(!Model::conn()->table_exist($this->table)){
			Model::conn()->table($this->table)->addColumn($this->id, 'varchar(50)')
						->addColumn($this->access, 'int(12)')
						->addColumn($this->session, 'longtext')
						->primary($this->id)
						->index($this->access)
						->create();
		}
		self::$exist = true;
	}

	// Convert to Array
	private function to_array(array|object $data):array
	{
		if(is_object($data)){
			return json_decode(json_encode($data), true);
		}
		return $data;
	}
}