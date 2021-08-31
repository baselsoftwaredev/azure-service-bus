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
 * PHP version 5
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/windowsazure/azure-sdk-for-php
 * @category  Microsoft
 */

namespace Tests\unit\WindowsAzure\Common\Internal\Serialization;

use PHPUnit\Framework\TestCase;
use Tests\framework\TestResources;
use WindowsAzure\Common\Internal\Serialization\JsonSerializer;

/**
 * Unit tests for class XmlSerializer.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class JsonSerializerTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\JsonSerializer::objectSerialize
     */
    public function testObjectSerialize(): void
    {
        $testData = TestResources::getSimpleJson();
        $rootName = 'testRoot';
        $expected = "{\"$rootName\":{$testData['jsonObject']}}";

        $actual = JsonSerializer::objectSerialize((object) $testData['dataObject'], $rootName);

        self::assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\JsonSerializer::unserialize
     */
    public function testUnserializeArray(): void
    {
        $jsonSerializer = new JsonSerializer();
        $testData = TestResources::getSimpleJson();
        $expected = $testData['dataArray'];

        $actual = $jsonSerializer->unserialize($testData['jsonArray']);

        self::assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\JsonSerializer::unserialize
     */
    public function testUnserializeObject(): void
    {
        $jsonSerializer = new JsonSerializer();
        $testData = TestResources::getSimpleJson();
        $expected = $testData['dataObject'];

        $actual = $jsonSerializer->unserialize($testData['jsonObject']);

        self::assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\JsonSerializer::unserialize
     */
    public function testUnserializeEmptyString(): void
    {
        $jsonSerializer = new JsonSerializer();
        $testData = '';
        $expected = null;

        $actual = $jsonSerializer->unserialize($testData);

        self::assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\JsonSerializer::unserialize
     */
    public function testUnserializeInvalidString(): void
    {
        $jsonSerializer = new JsonSerializer();
        $testData = '{]{{test]';
        $expected = null;

        $actual = $jsonSerializer->unserialize($testData);

        self::assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\JsonSerializer::serialize
     */
    public function testSerialize(): void
    {
        $jsonSerializer = new JsonSerializer();
        $testData = TestResources::getSimpleJson();
        $expected = $testData['jsonArray'];

        $actual = $jsonSerializer->serialize($testData['dataArray']);

        self::assertEquals($expected, $actual);
    }
}
