<?php
/**
 * Project: Cloud Bill Master Session Handler
 * Author Name: Showket Ahmed
 * Author Email: riyadhtayf@gmail.com
 */

// Namespace
namespace CBM\Session;

class SessionConnection
{
	// Database Connection
	private static object|null $conn = null;

	// Load Instance
    /**
     * @param object $pdo Optional Argument
     */
	public static function config(object $pdo = null)
	{
		self::$conn = $pdo;
	}

    // Get PDO Connection Object
	public static function conn():object|null
	{
        return self::$conn;
	}
}