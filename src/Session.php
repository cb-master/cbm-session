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

class Session
{
    // Start Session
	private static function start()
	{
		(new Handler)->begin();
	}

	// Destroy Session
	public static function end()
	{
		self::start();
		session_unset();
		session_destroy();
		return true;
	}

	/** put data into the session **/
	public static function set(string $key, mixed $value, string $for = "APP"):bool
	{
		// Start
		self::start();
		// Set Session Value
		if($_SESSION[$for][$key] = $value){
            return true;
        }
        return false;
	}

	// Get Session Value
	public static function get(string $key, string $for = "APP"):mixed
	{
		// Start
		self::start();
		// Get Session Data
		return $_SESSION[$for][$key] ?? '';
	}

	// Unset Session
	public static function pop(string $key, string $for = "APP"):void
	{
		// Start
		self::start();
		if(isset($_SESSION[$for][$key]))
		{
			unset($_SESSION[$for][$key]);
		}
	}
}