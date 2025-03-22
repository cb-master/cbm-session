<?php
/**
 * Project: Cloud Bill Master Session Handler
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\SessionHelper;

use CBM\Session\SessionConnection;

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
    private static string $id = "ses_id";

    // Table Name Var
    private static $table = "sessions";

    // Data Var
    private static $session = "ses_data";

    // Last Update
    private static $access = "ses_last_access";

	// Table Exist
	private static Bool $exist = false;

	// Start Session
    protected static function start()
	{
        // Start Session
		if(session_status() !== PHP_SESSION_ACTIVE)
		{
			if(SessionConnection::conn()){
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
		$stmt = SessionConnection::conn()->prepare($sql);
		$stmt->execute();
		$data = json_decode(json_encode($stmt->fetch()), true);
		
		// $dbData = Model::table($this->table)->where([$this->id => $id])->single();
		// $data = json_decode(json_encode($dbData), true);
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
		$stmt = SessionConnection::conn()->prepare($sql);
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
		$stmt = SessionConnection::conn()->prepare($sql);
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
		$stmt = SessionConnection::conn()->prepare($sql);
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
			$stmt = SessionConnection::conn()->prepare("SHOW TABLES");
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
		$stmt = SessionConnection::conn()->prepare($sql);
		$stmt->execute();
	}
}