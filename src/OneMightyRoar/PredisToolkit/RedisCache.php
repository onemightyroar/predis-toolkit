<?php
/**
 * Predis-Toolkit
 *
 * @copyright 2013 One Mighty Roar
 * @link http://onemightyroar.com
 */

namespace OneMightyRoar\PredisToolkit;

use Desarrolla2\Cache\Adapter\AbstractAdapter;
use OneMightyRoar\PredisToolkit\Exceptions\InvalidFormatException;

/**
 * Class RedisCache
 *
 * @package OneMightyRoar\PredisToolkit
 */
class RedisCache
{
    /**
     * Get an item from the cache. If the item doesn't exist, the callable is run. Data returned by the callable is
     * cached for the specified amount of time.
     *
     * @param mixed $key
     * @param int $time
     * @param null $callback
     * @return mixed The resulting cache data
     * @access public
     */
    public static function get($key, $cache_time = 0, $callback = null)
    {
        if ($key instanceof CacheableKeyInterface && func_num_args() == 2) {
            $callback = func_get_arg(1);
            $cache_time = $key->getExpireTime();
        }

        $redis = Redis::getInstance();

        $type = ($key instanceof AbstractKeyDefinition)
            ? $key->type
            : 'string';

        if ($redis->exists((string)$key)) {
            $method = 'get' . ucfirst($type);
            return self::$method($key);
        } elseif (null !== $callback) {
            $data = call_user_func($callback);
            $method = 'set' . ucfirst($type);

            if (null !== $data) {
                self::$method($key, $data);

                // Set expiration if we're given a time. Otherwise, cache forever
                if ($cache_time) {
                    $redis->expire($key, $cache_time);
                }
            }

            return $data;
        }

        return null;
    }

    /**
     * Invalidate a cache item by key
     * @param $key
     * @return bool
     * @access public
     */
    public static function invalidate($key)
    {
        $redis = Redis::getInstance();
        return $redis->del((string)$key);
    }

    private static function getString($key)
    {
        $redis = Redis::getInstance();
        return unserialize($redis->get($key));
    }

    private static function setString($key, $data)
    {
        $redis = Redis::getInstance();
        return $redis->set($key, serialize($data));
    }

    private static function getHash($key)
    {
        $redis = Redis::getInstance();
        return $redis->hgetall($key);
    }

    private static function setHash($key, $data)
    {
        // Make sure this array is associative
        if (!(bool)count(array_filter(array_keys($data), 'is_string'))) {
            throw new InvalidFormatException('When using a key with type "hash" the data must be an associative array');
        }

        $redis = Redis::getInstance();
        foreach ($data as $index => $value) {
            $redis->hset($key, $index, $value);
        }
    }
}
