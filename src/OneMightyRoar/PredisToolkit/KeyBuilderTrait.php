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
 * Trait KeyTrait
 *
 * @package OneMightyRoar\PredisToolkit
 */
trait KeyBuilderTrait
{

    /**
     * Easy static shortcut for getting a key as a string
     * @return string
     */
    public static function get()
    {
        $reflection = new \ReflectionClass(__CLASS__);
        $me = $reflection->newInstanceArgs(func_get_args());

        $key = (string) $me;

        // Clean up and return
        unset($me, $reflection);
        return $key;
    }

    /**
     * Return an array containing all the key pieces to build the full key
     *
     * @return array
     */
    public function getKeySegments()
    {
        if (func_num_args() >= 2) {
            list($class_name, $keys_array) = func_get_args();
        } else {
            $class_name = __CLASS__;
            $keys_array = array();
        }

        // Get the parent class
        $parent_class = get_parent_class($class_name);

        if (in_array('OneMightyRoar\PredisToolkit\KeyBuilderTrait', class_uses($parent_class))) {
            $keys_array = parent::getKeySegments($parent_class, $keys_array);
        }

        $keys_array[] = self::getKeySegment();
        return $keys_array;
    }

    /**
     * Return the string value of the key
     * @return string
     * @access public
     */
    public function toString()
    {
        return implode(':', $this->getKeySegments());
    }
}
