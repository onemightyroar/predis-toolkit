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
 * Client
 *
 * Extending class for Predis client to add extra functionality
 *
 * @package OneMightyRoar\PredisToolkit
 *
 */
class Client extends \Predis\Client
{

    /**
     * "Hashmap" that stores the queues based on key
     *
     * @var array
     */
    private $queued_command_map = array();

    /**
     * Queue a redis command for later execution. Different queues are maintianed by the provided key.
     *
     * @access public
     * @param string $key
     * @param string $method
     * @param array $arguments
     */
    public function queueCommand($key, $method, array $arguments = array())
    {

        // Create a CommandInterface object to queue for execution later
        $command = $this->getProfile()->createCommand($method, $arguments);

        $this->queued_command_map[$key][] = $command;
    }

    /**
     * Executes a queue of redis commands in a transaction
     *
     * @access public
     * @param string $key
     * @return bool
     */
    /**
    public function executeQueue($key)
    {
        // Do we have commands queued?
        if (isset($this->queued_command_map[$key]) && count($this->queued_command_map[$key]) > 0) {
            $multi = $this->multiExec();

            // Run all the queued commands in the tranaction
            foreach ($this->queued_command_map[$key] as $command) {
                $multi->executeCommand($command);
            }

            $multi->exec();
        }

        return true;
    }

    /**
     * Clear a queue based on a key
     *
     * @access public
     * @param $key
     */
    public function clearQueue($key)
    {
        unset($this->queued_command_map[$key]);
    }

    /**
     * Magic method for all calls to convert KeyDefinitions to strings and pass through to \Predis\Client
     *
     * @access public
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = array())
    {
        // If the first argument is a AbstractKeyDefinition instead of a string, cast to a string
        if (isset($arguments[0]) && $arguments[0] instanceof AbstractKeyDefinition) {
            $arguments[0] = (string) $arguments[0];
        }

        // Pass the call through to the Predis client
        return parent::__call($method, $arguments);
    }
}
