<?php

/**
 * LICENSE: Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0.
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * PHP version 7.4
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-vbus
 * @category  Microsoft
 */

namespace Tests\unit\WindowsAzure\Common;

use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use WindowsAzure\Common\CloudConfigurationManager;
use WindowsAzure\Common\Internal\ConnectionStringSource;

/**
 * Unit tests for class CloudConfigurationManager.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class CloudConfigurationManagerTest extends TestCase
{
    private string $_key = 'my_connection_string';
    private string $_value = 'connection string value';

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp(): void
    {
        $isInitialized = new ReflectionProperty(CloudConfigurationManager::class, '_isInitialized');
        $isInitialized->setAccessible(true);
        $isInitialized->setValue(false);

        $sources = new ReflectionProperty(CloudConfigurationManager::class, '_sources');
        $sources->setAccessible(true);
        $sources->setValue([]);
    }

    /**
     * @covers \WindowsAzure\Common\CloudConfigurationManager::getConnectionString
     * @covers \WindowsAzure\Common\CloudConfigurationManager::_init
     */
    public function testGetConnectionStringFromEnvironmentVariable()
    {
        putenv("$this->_key=$this->_value");

        $actual = CloudConfigurationManager::getConnectionString($this->_key);

        $this->assertEquals($this->_value, $actual);

        putenv($this->_key);
    }

    /**
     * @covers \WindowsAzure\Common\CloudConfigurationManager::getConnectionString
     */
    public function testGetConnectionStringDoesNotExist()
    {
        $actual = CloudConfigurationManager::getConnectionString('does not exist');

        $this->assertEmpty($actual);
    }

    /**
     * @covers \WindowsAzure\Common\CloudConfigurationManager::registerSource
     * @covers \WindowsAzure\Common\CloudConfigurationManager::_init
     */
    public function testRegisterSource()
    {
        $expectedKey = $this->_key;
        $expectedValue = $this->_value . 'extravalue';

        CloudConfigurationManager::registerSource(
            'my_source',
            function ($key) use ($expectedKey, $expectedValue) {
                if ($key == $expectedKey) {
                    return $expectedValue;
                }
                return null;
            }
        );

        $actual = CloudConfigurationManager::getConnectionString($expectedKey);
        $this->assertEquals($expectedValue, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\CloudConfigurationManager::registerSource
     * @covers \WindowsAzure\Common\CloudConfigurationManager::_init
     */
    public function testRegisterSourceWithPrepend()
    {
        $expectedKey = $this->_key;
        $expectedValue = $this->_value . 'extravalue2';
        putenv("$this->_key=wrongvalue");

        CloudConfigurationManager::registerSource(
            'my_source',
            function ($key) use ($expectedKey, $expectedValue) {
                if ($key == $expectedKey) {
                    return $expectedValue;
                }
                return null;
            },
            true
        );

        $actual = CloudConfigurationManager::getConnectionString($expectedKey);
        $this->assertEquals($expectedValue, $actual);

        putenv($this->_key);
    }

    /**
     * @covers \WindowsAzure\Common\CloudConfigurationManager::unregisterSource
     * @covers \WindowsAzure\Common\CloudConfigurationManager::_init
     */
    public function testUnRegisterSource()
    {
        $expectedKey = $this->_key;
        $expectedValue = $this->_value . 'extravalue3';
        $name = 'my_source';
        CloudConfigurationManager::registerSource(
            $name,
            function ($key) use ($expectedKey, $expectedValue) {
                if ($key == $expectedKey) {
                    return $expectedValue;
                }
                return null;
            }
        );

        $callback = CloudConfigurationManager::unregisterSource($name);

        $actual = CloudConfigurationManager::getConnectionString($expectedKey);
        $this->assertEmpty($actual);
        $this->assertNotNull($callback);
    }

    /**
     * @covers \WindowsAzure\Common\CloudConfigurationManager::registerSource
     * @covers \WindowsAzure\Common\CloudConfigurationManager::_init
     */
    public function testRegisterSourceWithDefaultSource()
    {
        $expectedKey = $this->_key;
        $expectedValue = $this->_value . 'extravalue5';
        CloudConfigurationManager::unregisterSource(ConnectionStringSource::ENVIRONMENT_SOURCE);
        putenv("$expectedKey=$expectedValue");

        CloudConfigurationManager::registerSource(ConnectionStringSource::ENVIRONMENT_SOURCE);

        $actual = CloudConfigurationManager::getConnectionString($expectedKey);
        $this->assertEquals($expectedValue, $actual);

        putenv($expectedKey);
    }

    /**
     * @covers \WindowsAzure\Common\CloudConfigurationManager::unregisterSource
     * @covers \WindowsAzure\Common\CloudConfigurationManager::_init
     */
    public function testUnRegisterSourceWithDefaultSource()
    {
        $expectedKey = $this->_key;
        $expectedValue = $this->_value . 'extravalue4';
        $name = 'my_source';
        CloudConfigurationManager::registerSource(
            $name,
            function ($key) use ($expectedKey, $expectedValue) {
                if ($key == $expectedKey) {
                    return $expectedValue;
                }
                return null;
            }
        );

        $callback = CloudConfigurationManager::unregisterSource(ConnectionStringSource::ENVIRONMENT_SOURCE);

        $actual = CloudConfigurationManager::getConnectionString($expectedKey);
        $this->assertEquals($expectedValue, $actual);
        $this->assertNotNull($callback);
    }
}
