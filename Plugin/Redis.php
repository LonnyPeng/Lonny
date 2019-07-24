<?php

namespace Plugin;

class Redis
{
	const REDIS_ENABLED = true;
	const REDIS_HOST = "127.0.0.1";
	const REDIS_PORT = "6379";
	const REDIS_PASSWORD = "redis@123456";
	const REDIS_DATABASE = 0;

	/**
	 * [init 初始化]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @return   [type]                  [description]
	 */
	private static function init()
	{
	    if (!self::REDIS_ENABLED) {
	        return NULL;
	    }

	    if (!class_exists("Redis")) {
	        return NULL;
	    }

	    try {
	        $redis = new \Redis();
	        $redis->pconnect(self::REDIS_HOST, self::REDIS_PORT);
	        if (self::REDIS_PASSWORD !== '') {
	            $redis->auth(self::REDIS_PASSWORD);
	        }
	        $redis->select(self::REDIS_DATABASE);

	        return $redis;
	    } catch (\RedisException $e) {
	        echo $e->getMessage() . PHP_EOL;exit();
	    }
	}

	/**
	 * [get 查询]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    string                  $key [description]
	 * @return   [type]                       [description]
	 */
	public static function get($key = "")
	{
		if (!$redis = self::init()) {
		    return NULL;
		}

		if ($redis->exists($key)) {
		    return $redis->get($key);
		} else {
			return NULL;
		}
	}

	/**
	 * [set 设置]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    string                  $key   [description]
	 * @param    string                  $value [description]
	 * @param    integer                 $ttl   [description]
	 */
	public static function set($key = "", $value = "", $ttl = 0)
	{
		if (!$redis = self::init()) {
		    return NULL;
		}

		//加锁
        $newExpire = self::getLock($redis, $key);

		if ($ttl > 0) {
		    $status = $redis->set($key, $value, $ttl);
		} else {
		    $status = $redis->set($key, $value);
		}

		//解锁
		self::releaseLock($redis, $key, $newExpire);

		return $status;
	}

	/**
	 * [del 删除]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    string                  $key [description]
	 * @return   [type]                       [description]
	 */
	public static function del($key = "")
	{
	    if (!$redis = self::init()) {
	        return NULL;
	    }

	    return $redis->del($key);
	}

	/**
	 * [getHash 查询哈希]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    string                  $key [description]
	 * @return   [type]                       [description]
	 */
	public static function getHash($key = "")
	{
	    if (!$redis = self::init()) {
	        return NULL;
	    }

	    if ($redis->exists($key)) {
	        return $redis->hGetAll($key);
	    } else {
	        return NULL;
	    }
	}

	/**
	 * [getHashKey 查询哈希KEY]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    string                  $key    [description]
	 * @param    string                  $mapKey [description]
	 * @return   [type]                          [description]
	 */
	public static function getHashKey($key = "", $mapKey = "")
	{
		$data = self::getHash($key);
		if (isset($data[$mapKey])) {
			return $data[$mapKey];
		} else {
			return NULL;
		}
	}

	/**
	 * [setHash 设置哈希]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    string                  $key  [description]
	 * @param    array                   $data [description]
	 */
	public static function setHash($key = '', $data = array())
	{
	    if (!$redis = self::init()) {
	        return NULL;
	    }

	    $newExpire = self::getLock($redis, $key);

	    $status = $redis->hMset($key, $data);

	    self::releaseLock($redis, $key, $newExpire);

	    return $status;
	}

	/**
	 * [setHashKey 设置哈希KEY]
	 * @Author   Lonny
	 * @Email    lonnypeng@baogongpo.com
	 * @DateTime 2019-04-29
	 * @param    string                  $key    [description]
	 * @param    string                  $mapKey [description]
	 * @param    array                   $data   [description]
	 */
	public static function setHashKey($key = "", $mapKey = "", $data = array())
	{
		if (!$redis = self::init()) {
		    return NULL;
		}

		$old_data = self::getHash($key);
		$old_data[$mapKey] = $data;

		return self::setHash($key, $old_data);
	}

	/**
	 * @desc 获取锁键名
	 */
	private static function getLockCacheKey($key)
	{
	    return "lock_{$key}";
	}

	/**
	 * @desc 获取锁
	 *
	 * @param key string | 要上锁的键名
	 * @param timeout int | 上锁时间
	 */
	private static function getLock($redis = null, $key = '', $timeout = 3)
	{
	    $lockCacheKey = self::getLockCacheKey($key);
	    $expireAt = time() + $timeout;
	    $isGet = (bool) $redis->setnx($lockCacheKey, $expireAt);
	    if ($isGet) {
	        return $expireAt;
	    }

	    while (1) {
	        usleep(10);
	        $time = time();
	        $oldExpire = $redis->get($lockCacheKey);
	        if ($oldExpire >= $time) {
	            continue;
	        }
	        $newExpire = $time + $timeout;
	        $expireAt = $redis->getset($lockCacheKey, $newExpire);
	        if ($oldExpire != $expireAt) {
	            continue;
	        }

	        $isGet = $newExpire;
	        break;
	    }
	    
	    return $isGet;
	}

	/**
	 * @desc 释放锁
	 *
	 * @param key string | 加锁的字段
	 * @param newExpire int | 加锁的截止时间
	 *
	 * @return bool | 是否释放成功
	 */
	private static function releaseLock($redis = null, $key = '', $newExpire = null)
	{
	    $lockCacheKey = self::getLockCacheKey($key);
	    if ($newExpire >= time()) {
	        return $redis->del($lockCacheKey);
	    }

	    return true;
	}
}