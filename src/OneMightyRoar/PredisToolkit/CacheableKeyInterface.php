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
 * Trait CacheableKeyInterface
 *
 * @package OneMightyRoar\PredisToolkit
 */
interface CacheableKeyInterface
{
    public static function getExpireTime();

}

