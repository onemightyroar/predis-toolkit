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
 * AbstractKeyDefinition
 *
 * Extending class for Predis client to add extra functionality
 *
 * @package OneMightyRoar\PredisToolkit
 * @abstract
 */
abstract class AbstractKeyDefinition
{
    /**
     * The type of storage in Redis. Acceptable values are string, set, zset, hash, list
     * @var string
     */
    public $type = 'string';

    /**
     * Return the string value of the key
     * @return string
     */
    abstract public function toString();

    /**
     * Return the string value of the key
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
