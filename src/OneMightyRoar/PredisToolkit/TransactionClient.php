<?php
/**
 * Predis-Toolkit
 *
 * Additional classes and functionality to extend Predis
 *
 * @copyright	2013 One Mighty Roar
 * @link	 	http://onemightyroar.com
 */

namespace OneMightyRoar\PredisToolkit;

/**
 * TransactionClient
 *
 * A class that automates the queueing up all redis commands for later execution, based on a key.
 *
 * @package OneMightyRoar\PredisToolkit
 */
class TransactionClient
{

    /**
     * The Redis object
     *
     * @var \OneMightyRoar\PredisToolkit\Redis
     * @access private
     */
    private $redis;

    /**
     * The Redis Client object
     *
     * @var \OneMightyRoar\PredisToolkit\Client
     * @access private
     */
    private $client;

    /**
     * Key to uniquely identify the command queue
     *
     * @var string Optional key to identify the stack. If not provided, it will be generated with unique()
     * @access private
     */
    private $key;

    /**
     * Constructor
     *
     * @access public
     * @param string $key The key to use when queueing these commands. Uses uniqid() when not provided.
     */
    public function __construct($key = null)
    {
        $this->redis = Redis::instance();
        $this->client = $this->redis->get_client();
        $this->key = is_null($key) ? uniqid() : $key;
    }
    
    /**
     * Run all queued redis commands
     * 
     * @access public
     * @return mixed
     */
    public function processQueue()
    {
        return $this->client->executeQueue($this->key);
    }

    /**
     * Magic method for all calls. Passes the call through to the client with queue key
     *
     * @access public
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = array())
    {
        $this->client->queueCommand($this->key, $method, $arguments);
    }
}
