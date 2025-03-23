<?php
/**
 * Project: Cloud Bill Master Session Handler
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Session;

class SessionConfig
{
	// PDO Connection Object
	private static object|null $conn = null;

	// Session Params
	private static array $config = [];

	// Session Cookies
	private static array $cookie = [];

	// Load PDO Instance
    /**
     * @param object $pdo Optional Argument
     */
	public static function pdo(object $pdo = null)
	{
		self::$conn = $pdo;
	}

	// Config Session Params
	/**
	 * @param array $array Optional Arguments.
	 */
	public static function setConfig(array $array):void
	{
		self::$config = array_merge(self::$config, $array);
	}

	// Config Session Cookies
	/**
	 * @param array $array Optional Arguments.
	 */
	public static function setCookie(array $array):void
	{
		self::$cookie = array_merge(self::$cookie, $array);
	}

	// Get Session Configs
	public static function getConfig():array
	{
		return self::$config;
	}

	// Get Session Cookies
	public static function getCookie():array
	{
		return self::$cookie;
	}

    // Get PDO Connection Object
	public static function conn():object|null
	{
        return self::$conn;
	}
}