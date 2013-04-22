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

use \DomainException;

/**
 * Redis
 *
 * A singleton which abstracts the user from the Predis client object.
 *
 * @package OneMightyRoar\PredisToolkit
 */
class Redis extends Singleton
{

    /**
     * Client wrapper for Redis APIs
     *
     * @var \OneMightyRoar\PredisToolkit\Client
     * @access private
     */
    private $client;

    /**
     * Pre-defined keys to prefix a namespace onto all redis calls
     *
     * @var array
     * @access private
     */
    private $keys;

    /**
     * Initialize the Redis singleton with the proper Redis connection config
     *
     * @param array $connection_config Connection credentials for Redis
     * @param array $key_store_keys Array of values to build Redis keys with
     */
    public function init(array $connection_config, array $key_store_keys = array())
    {
        $this->keys = $key_store_keys;
        $this->client = new Client($connection_config);
    }

    /**
     * Getter for the client object
     *
     * @access public
     * @return \OneMightyRoar\PredisToolkit\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Getter for the Redis keys config
     *
     * @access public
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Get the name of a Redis key
     *
     * Builds a redis key name intelligently by
     * adding in the ':' markers in different levels
     *
     * @param string $key_name
     * @access public
     * @return string
     * @throws \DomainException
     */
    public function getKey($key_name)
    {
        $master_key = $this->keys['master'];

        // Grab our key based on our config
        if (!in_array($key_name, $this->keys, true)) {
            throw new DomainException('Redis key not pre-defined');
        }

        return $master_key . ':' . $this->keys[ $key_name ];
    }

    /**
     * Magic method for all calls. Passes the call through to the client
     *
     * @access public
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = array())
    {
        if (!$this->client->isConnected()) {
            $this->client->connect();
        }

        // Pass the call through to the actual client
        return call_user_func_array(array($this->client, $method), $arguments);
    }
}
