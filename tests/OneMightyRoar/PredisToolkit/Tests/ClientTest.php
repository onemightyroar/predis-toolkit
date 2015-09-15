<?php
/**
 * Predis-Toolkit
 *
 * Additional classes and functionality to extend Predis
 *
 * @copyright 2013 One Mighty Roar
 * @link http://onemightyroar.com
 */

namespace OneMightyRoar\PredisToolkit\Test;

use OneMightyRoar\PredisToolkit\Client;
use PHPUnit_Framework_TestCase;

/**
 * Tests the {@link Client} class.
 */
class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * Properties
     */

    /**
     * The {@link Client} to test.
     *
     * @type Client
     */
    private $client;

    /**
     * Methods
     */

    public function setUp()
    {
        parent::setUp();

        $this->client = new Client($this->getMockConnection());
    }

    private function getMockConnection()
    {
        $mock_connection = $this->getMock('Predis\Connection\ConnectionInterface');

        return $mock_connection;
    }

    public function testSupportedCommandsPassThroughMagicCallerCorrectly()
    {
        $profile = $this->client->getProfile();

        $commands = $profile->getSupportedCommands();

        $key = 'KEY:NAME';

        foreach ($commands as $cmd => $cls) {
            // quit command is different
            if ('quit' === strtolower($cmd)) {
                $this->client->getConnection()->expects($this->at(0))->method('disconnect')
                    ->will($this->returnValue(null));

                $val = $this->client->$cmd($key);
                $this->assertNull($val);
            } else {
                $command = null;

                $this->client->getConnection()->expects($this->at(0))->method('executeCommand')
                    ->with($this->callback(function ($com) use (&$command) {
                        $command = $com;
                        return true;
                    }))
                    ->will($this->returnValue('\n'));

                $val = $this->client->$cmd($key, []);
                $this->assertInstanceOf($cls, $command);
            }
        }
    }
}
