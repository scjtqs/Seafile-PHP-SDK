<?php

namespace Seafile\Client\Tests\Http;

use Seafile\Client\Http\Client;
use Seafile\Client\Tests\TestCase;

/**
 * Client test
 *
 * @package   Seafile\Resource
 * @author    Rene Schmidt DevOps UG (haftungsbeschränkt) & Co. KG <rene+_seafile_github@sdo.sh>
 * @copyright 2015-2017 Rene Schmidt DevOps UG (haftungsbeschränkt) & Co. KG <rene+_seafile_github@sdo.sh>
 * @license   https://opensource.org/licenses/MIT MIT
 * @link      https://github.com/rene-s/seafile-php-sdk
 * @covers    \Seafile\Client\Http\Client
 */
class ClientTest extends TestCase
{
    /**
     * Test that base_uri is empty by default.
     *
     * @return void
     */
    public function testBaseUriEmpty()
    {
        $client = new Client();
        self::assertEmpty((string)$client->getConfig('base_uri'));
    }

    /**
     * Test that base_uri not empty when a value has been set.
     *
     * @return void
     */
    public function testBaseUriNotEmpty()
    {
        $client = new Client(['base_uri' => 'http://example.com']);
        self::assertSame('http://example.com/api2', (string)$client->getConfig('base_uri'));
    }
}
