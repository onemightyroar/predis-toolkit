<?php
/**
 * PHP-Redis-Transaction-Queue
 *
 * Allows the easy use of queueing multiple Redis commands into a "transaction" to be executed at one time
 *
 * @copyright	2013 One Mighty Roar
 * @link	 	http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Redis_Transaction_Queue;

/**
 * Client
 *
 * Extending class for Predis client to add extra functionality
 *
 * @package OneMightyRoar\PHP_Redis_Transaction_Queue
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
	 * @param array $arguements
	 */
	public function queueCommand($key, $method, $arguments = array())
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
	 * @return array
	 */
	public function executeQueue($key)
    {
		// Do we have commands queued?
		if(isset($this->queued_command_map[$key]) && count($this->queued_command_map[$key]) > 0) {
			$multi = $this->multiExec();

			// Run all the queued commands in the tranaction
			foreach($this->queued_command_map[$key] as $command) {
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
	 * @param string $key
	 */
	public function clearQueue($key)
    {
		unset($this->queued_command_map[$key]);
	}

}
