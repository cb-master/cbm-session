<?php
/**
 * Project: Cloud Bill Master Session Handler
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Session;

use CBM\SessionHelper\Handler;

final class Session Extends Handler
{
	// Set Session Data
	/**
	 * @param array $array - Required Argument
	 * @param string $for - Default is 'APP'. Session Will Set Like $_SESSION['APP']['key']
	 */
	public static function set(array $array, string $for = "APP"):bool
	{
		$for = strtoupper($for);
		// Start Session
		self::start();

		// Set Session Value
		foreach($array as $key => $val){
			$_SESSION[$for][$key] = $val;
		}
        return true;
		foreach($array as $key => $val){
			$_SESSION[$for][$key] = $val;
		}
        return true;
	}

	/**
	 * @param string $key - Required Argument
	 * @param string $for - Default is 'APP'. Session Will Return $_SESSION['APP']['key']
	 */
	public static function get(string $key, string $for = "APP"):string|array
	{
		$for = strtoupper($for);
		// Start Session
		self::start();
		$for = strtoupper($for);
		// Start Session
		self::start();
		// Get Session Data
		return $_SESSION[$for][$key] ?? '';
	}

	/**
	 * @param string|array $key - Required Argument. For Array, Set Index Array of Keys.
	 * @param string $for - Default is 'APP'. Session Will Unset $_SESSION['APP']['key']
	 */
	public static function pop(string|array $key, string $for = "APP"):bool
	{
		$for = strtoupper($for);
		// Start Session
		self::start();
		
		$key = is_string($key) ? [$key] : $key;

		foreach($key as $search){
			if(isset($_SESSION[$for][$search]))
			{
				unset($_SESSION[$for][$search]);
			}
		}

		return true;
	}

	// Destroy Session
	public static function end():bool
	{
		// Start Session
		self::start();
		// Unset Session
		session_unset();
		// Destroy Session
		session_destroy();
		return true;
	}
}