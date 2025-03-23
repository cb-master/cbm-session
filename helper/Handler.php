<?php
/**
 * Project: Cloud Bill Master Session Handler
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\SessionHelper;

use CBM\Session\SessionConfig;

class Handler
{
	// Session Name
	public static String $name = 'laika';

    // DB ID
    private static string $id = "ses_id";

    // Table Name Var
    private static $table = "sessions";

    // Data Var
    private static $session = "ses_data";

    // Last Update
    private static $access = "ses_last_access";

	// Table Exist
	private static Bool $exist = false;

	// Handler Config
	private static function config():array
	{
		return [
			'session.use_only_cookies'	=>	true,
			'session.use_strict_mode'	=>	true,
			'session.gc_probability'	=>	1,
			'session.gc_divisor'		=>	100,
			'session.gc_maxlifetime'	=>	strtotime("+10 minutes") - time()
		];
	}

	// Handler Cookies
	private static function cookies():array
	{
		return [
			"path"      =>  '/cbm-laika',
			"secure"    =>  true,
			"httponly"  =>  true,
			"samesite"  =>  "Strict"
		];
	}

	// Start Session
    protected static function start()
	{
        // Start Session
		if(session_status() !== PHP_SESSION_ACTIVE)
		{
			if(SessionConfig::conn()){
				// Create Table if Not Exist
				if(!self::$exist){
					self::session_table_exist();
				}

				session_set_save_handler(
					[__CLASS__, 'open'],
					[__CLASS__, 'close'],
					[__CLASS__, 'read'],
					[__CLASS__, 'write'],
					[__CLASS__, 'destroy'],
					[__CLASS__, 'gc']
				);
				// register_shutdown_function("session_write_close");
			}

			$configs = array_merge(self::config(), SessionConfig::getConfig());

			// Set Session Name
            session_name(self::$name);
			// Set Session INI
			array_filter($configs, function($val, $key){
				ini_set($key, $val);
			}, ARRAY_FILTER_USE_BOTH);
            // Set Session Parameters
            session_set_cookie_params(array_merge(self::cookies(), SessionConfig::getCookie()));
            // Start Session
            session_start();
        }
	}

	// Open DB Connection
	public static function open():bool
	{
		return true;
	}

	// Close DB Connection
	public static function close():bool
	{
		return true;
	}

	// Read DB Data
	public static function read($id):string
	{
		$sql = "SELECT * FROM ".self::$table." WHERE ".self::$id."='{$id}'";
		$stmt = SessionConfig::conn()->prepare($sql);
		$stmt->execute();
		$data = json_decode(json_encode($stmt->fetch()), true);
		return $data[self::$session] ?? '';
	}

	// Insert DB Data
	public static function write($id, $data):bool
	{
		// Create time stamp
		$access = time();
		// Insert/Update Data
		$array = [
			self::$id       =>  $id,
			self::$access   =>  $access,
			self::$session  =>  $data
		];

		$sql = "REPLACE INTO `".self::$table."` (".implode(',', array_keys($array)).") VALUES (?,?,?)";
		// Prepare Statement
		$stmt = SessionConfig::conn()->prepare($sql);
		// Execute Statement
		$stmt->execute(array_values($array));
		// Count Effected Rows
		$result = (int) $stmt->rowCount();
		return $result ? true : false;
	}

	// Destroy DB Data
	public static function destroy($id):bool
	{
		$sql = "DELETE FROM ".self::$table." WHERE ".self::$id."='{$id}'";
		// Prepare Statement
		$stmt = SessionConfig::conn()->prepare($sql);
		// Execute Statement
		$stmt->execute();
		// Count Effected Rows
		$result = (int) $stmt->rowCount();
		return $result ? true : false;
	}

	// Garbage Collection
	public static function gc($max):bool
	{
		$exp = time() - $max;
		$sql = "DELETE FROM ".self::$table." WHERE ".self::$access."<'{$exp}'";
		// Prepare Statement
		$stmt = SessionConfig::conn()->prepare($sql);
		// Execute Statement
		$stmt->execute();
		// Count Effected Rows
		$result = (int) $stmt->rowCount();
		return $result ? true : false;
	}

	// Create Table if Not Exist
	private static function session_table_exist()
	{
		if(!self::$exist){
			// Prepare Statement
			$stmt = SessionConfig::conn()->prepare("SHOW TABLES");
			$stmt->execute();
			// Execute Statement
			$result = $stmt->fetchAll();
			$result = json_decode(json_encode($result), true);
			foreach($result as $res){
				if(in_array(self::$table, $res)){
					self::$exist = true;
					return true;
				}
			}
			self::create_table();
			return true;
		}
		return self::$exist;
	}

	// Create Session Table
	private static function create_table():void
	{
		$sql = "CREATE TABLE `".self::$table."` (
				`".self::$id."` varchar(50) NOT NULL,
				`".self::$access."` int(10) DEFAULT NULL,
				`".self::$session."` longtext DEFAULT NULL,
				PRIMARY KEY (`".self::$id."`),
				KEY `".self::$access."` (`".self::$access."`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
		$stmt = SessionConfig::conn()->prepare($sql);
		$stmt->execute();
	}
}