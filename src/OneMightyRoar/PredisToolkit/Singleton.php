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
 * Singleton
 *
 * PHP 5.3+ Singleton Pattern
 * This class was originally taken from Paulus
 *
 * @author Trevor Suarez (Rican7)
 * @copyright 2013 Trevor Suarez
 * @link https://github.com/Rican7/Paulus/
 * @license https://github.com/Rican7/Paulus/blob/master/LICENSE
 *
 * @abstract
 * @package \OneMightyRoar\PredisToolkit
 */
abstract class Singleton
{

    /**
     * __construct
     *
     * Singleton constructor
     *
     * @access private
     */
    private function __construct()
    {
        // Do nothing
    }

    /**
     * __clone
     *
     * Don't allow cloning
     *
     * @final
     * @access private
     * @return void
     */
    final private function __clone()
    {
        // Do nothing
        // TODO: Possibly throw an exception here..
    }

    /**
     * instance
     *
     * Get the instance of the class, or create one if one doesn't exist yet
     *
     * @static
     * @final
     * @access public
     * @return Singleton
     */

    final public static function instance()
    {
        // Create an instance var to keep track of the instance
        static $instance = null;

        // If the instance doesn't exist yet
        if (is_null($instance)) {
            // Create it and keep a reference to it
            $instance = new static();
        }

        // Always return the instance
        return $instance;
    }


    /**
     * getInstance
     *
     * Alias the instance method
     *
     * @static
     * @final
     * @access public
     * @return Singleton
     */
    final public static function getInstance()
    {
        return static::instance();
    }
}
