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

namespace Tests\unit\WindowsAzure\Common\Internal\Serialization;

use PHPUnit\Framework\TestCase;
use Tests\framework\TestResources;
use WindowsAzure\Common\Internal\Serialization\XmlSerializer;
use WindowsAzure\Common\Models\ServiceProperties;

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
class XmlSerializerTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\XmlSerializer::unserialize
     * @covers \WindowsAzure\Common\Internal\Serialization\XmlSerializer::_sxml2arr
     */
    public function testUnserialize(): void
    {
        $xmlSerializer = new XmlSerializer();
        $propertiesSample = TestResources::getServicePropertiesSample();
        $properties = ServiceProperties::create($propertiesSample);
        $xml = $properties->toXml($xmlSerializer);
        $expected = $properties->toArray();

        $actual = $xmlSerializer->unserialize($xml);

        self::assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\XmlSerializer::serialize
     * @covers \WindowsAzure\Common\Internal\Serialization\XmlSerializer::_arr2xml
     */
    public function testSerialize(): void
    {
        $xmlSerializer = new XmlSerializer();
        $propertiesSample = TestResources::getServicePropertiesSample();
        $properties = ServiceProperties::create($propertiesSample);
        $expected = $properties->toXml($xmlSerializer);
        $array = $properties->toArray();
        $serializerProperties = [XmlSerializer::ROOT_NAME => ServiceProperties::$xmlRootName];

        $actual = $xmlSerializer->serialize($array, $serializerProperties);

        self::assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\XmlSerializer::serialize
     * @covers \WindowsAzure\Common\Internal\Serialization\XmlSerializer::_arr2xml
     */
    public function testSerializeAttribute(): void
    {
        $xmlSerializer = new XmlSerializer();
        $expected = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
            '<Object field1="value1" field2="value2"/>' . "\n";

        $object = [
            '@attributes' => [
                'field1' => 'value1',
                'field2' => 'value2',
            ],
        ];
        $serializerProperties = [XmlSerializer::ROOT_NAME => 'Object'];

        $actual = $xmlSerializer->serialize($object, $serializerProperties);

        self::assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\XmlSerializer::objectSerialize
     */
    public function testObjectSerializeSuccess(): void
    {
        $expected = "<DummyClass/>\n";
        $target = new DummyClass();

        $actual = XmlSerializer::objectSerialize($target, 'DummyClass');

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Serialization\XmlSerializer::objectSerialize
     */
    public function testObjectSerializeSuccessWithAttributes(): void
    {
        $expected = "<DummyClass testAttribute=\"testAttributeValue\"/>\n";
        $target = new DummyClass();
        $target->addAttribute('testAttribute', 'testAttributeValue');

        $actual = XmlSerializer::objectSerialize($target, 'DummyClass');

        self::assertEquals(
            $expected,
            $actual
        );
    }
}
