<?php
/**
 * Predis-Toolkit
 *
 * Additional classes and functionality to extend Predis
 *
 * @copyright 2013 One Mighty Roar
 * @link http://onemightyroar.com
 */

namespace OneMightyRoar\PredisToolkit;

/**
 * Trait CacheableKeyTrait
 *
 * @package OneMightyRoar\PredisToolkit
 */
trait CacheableKeyTrait
{

    protected static $expire_time = 0;

    public static function getExpireTime()
    {
        return static::$expire_time;
    }
}