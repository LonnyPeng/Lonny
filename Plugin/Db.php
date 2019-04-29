<?php

namespace Plugin;

class Db
{
	const DB_ENABLED = true;
	const DB_HOST = "127.0.0.1";
	const DB_PORT = "3306";
	const DB_USERNAME = "root";
	const DB_PASSWORD = "123456";
	const DB_DATABASE = "db_lonny";

	/**
	 * [init 初始化]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @return   [type]                  [description]
	 */
	private static function init()
	{
		if (!self::DB_ENABLED) {
		    return NULL;
		}

		try {
		    $options = array(
		        \PDO::ATTR_PERSISTENT => true,
		        \PDO::MYSQL_ATTR_FOUND_ROWS => true,
		    );
		    $dsn = sprintf("mysql:host=%s:%s;dbname=%s", self::DB_HOST, self::DB_PORT, self::DB_DATABASE);
		    $pdo = new \PDO($dsn, self::DB_USERNAME, self::DB_PASSWORD, $options);
		    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		    $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_NUM);

		    return $pdo;
		} catch (\PdoException $e) {
		    echo $e->getMessage() . PHP_EOL;exit();
		}
	}

	/**
	 * [exec description]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    string                  $sql  [description]
	 * @param    [type]                  $args [description]
	 * @return   [type]                        [description]
	 */
	public static function exec($sql = "", $args = null)
	{
		if (!$pdo = self::init()) {
			return NULL;
		}

	    if (1 === func_num_args()) {
	        return $pdo::exec($sql);
	    } else {
	        $stmt = self::prepareParams($sql, $args);
	        if (!$stmt) {
	            return false;
	        }
	    }

	    return $stmt->rowCount();
	}

	/**
	 * [getAll description]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    [type]                  $sql  [description]
	 * @param    [type]                  $args [description]
	 * @return   [type]                        [description]
	 */
	public static function getAll($sql, $args = null)
	{
		if (!$stmt = self::getStmt($sql, $args)) {
			return NULL;
		}

	    return $stmt->fetchAll();
	}

	/**
	 * [getPairs description]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    [type]                  $sql  [description]
	 * @param    [type]                  $args [description]
	 * @return   [type]                        [description]
	 */
	public static function getPairs($sql, $args = null)
	{
		if (!$stmt = self::getStmt($sql, $args)) {
			return NULL;
		}

	    return $stmt->fetchAll(\PDO::FETCH_COLUMN | \PDO::FETCH_UNIQUE);
	}

	/**
	 * [getColumn description]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    [type]                  $sql  [description]
	 * @param    [type]                  $args [description]
	 * @return   [type]                        [description]
	 */
	public static function getColumn($sql, $args = null)
	{
		if (!$stmt = self::getStmt($sql, $args)) {
			return NULL;
		}

	    return $stmt->fetchAll(\PDO::FETCH_COLUMN);
	}

	/**
	 * [getRow description]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    [type]                  $sql  [description]
	 * @param    [type]                  $args [description]
	 * @return   [type]                        [description]
	 */
	public static function getRow($sql, $args = null)
	{
		if (!$stmt = self::getStmt($sql, $args)) {
			return NULL;
		}

	    $row = $stmt->fetch();
	    $stmt->closeCursor();

	    return $row;
	}

	public static function getOne($sql, $args = null)
	{
		if (!$stmt = self::getStmt($sql, $args)) {
			return NULL;
		}

	    $value = $stmt->fetchColumn();
	    $stmt->closeCursor();

	    return $value;
	}

	/**
	 * [getStmt description]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    [type]                  $sql  [description]
	 * @param    [type]                  $args [description]
	 * @return   [type]                        [description]
	 */
	protected static function getStmt($sql, $args = null)
	{
		if (!$pdo = self::init()) {
			return NULL;
		}

		if (1 === func_num_args()) {
		    $stmt = $pdo->query($sql);
		    if (false === $stmt) {
		        return false;
		    }
		} else {
		    $stmt = self::prepareParams($sql, $args);
		    if (!$stmt) {
		        return false;
		    }
		}

		return $stmt;
	}

	/**
	 * [prepareParams description]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    string                  $sql    [description]
	 * @param    string                  $params [description]
	 * @return   [type]                          [description]
	 */
	protected static function prepareParams($sql = '', $params = '')
	{
		if (!$pdo = self::init()) {
			return NULL;
		}

	    if (is_scalar($params) || null === $params) {
	        $params = func_get_args();
	        array_shift($params);
	    }

	    $stmt = $pdo->prepare($sql);
	    if (!$stmt->execute((array) $params)) {
	        return false;
	    }

	    return $stmt;
	}
}