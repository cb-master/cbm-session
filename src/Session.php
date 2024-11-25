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

final class Session Extends Handler
{
	/** put data into the session **/
	public static function set(array $array, string $for = "APP"):bool
	{
		// Start Session
		self::instance()->start();

		// Set Session Value
		foreach($array as $key => $val){
			$_SESSION[$for][$key] = $val;
		}
        return true;
	}

	// Get Session Value
	public static function get(string $key, string $for = "APP"):string|array
	{
		// Start Session
		self::instance()->start();
		// Get Session Data
		return $_SESSION[$for][$key] ?? '';
	}

	// Unset Session
	public static function pop(string|array $key, string $for = "APP"):bool
	{
		// Start Session
		self::instance()->start();
		
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
		self::instance()->start();
		// Unset Session
		session_unset();
		// Destroy Session
		session_destroy();
		return true;
	}
}