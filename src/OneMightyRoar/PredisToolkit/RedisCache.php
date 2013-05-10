<?php
/**
 * Predis-Toolkit
 *
 * @copyright 2013 One Mighty Roar
 * @link http://onemightyroar.com
 */

namespace OneMightyRoar\PredisToolkit;

use OneMightyRoar\PredisToolkit\Exceptions\InvalidFormatException;

/**
 * Class RedisCache
 *
 * @package OneMightyRoar\PredisToolkit
 */
class RedisCache
{
    public static function get($key, $time, $callback = null)
    {
        $redis = Redis::getInstance();

        $type = ($key instanceof \OneMightyRoar\PredisToolkit\AbstractKeyDefinition)
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
                $redis->expire($key, $time);
            }

            return $data;
        }

        return null;
    }

    public static function invalidate($key)
    {
        $redis = Redis::getInstance();
        return $redis->del($key);
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
