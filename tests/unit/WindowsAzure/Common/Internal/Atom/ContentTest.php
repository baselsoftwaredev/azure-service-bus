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

namespace Tests\unit\WindowsAzure\Common\Internal\Atom;

use PHPUnit\Framework\TestCase;
use SimpleXMLElement;
use WindowsAzure\Common\Internal\Atom\Content;
use XMLWriter;

/**
 * Unit tests for class WrapAccessTokenResult.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-vbus
 * @version   Release: 0.5.0_2016-11
 * @category  Microsoft
 */
class ContentTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Content::__construct
     */
    public function testContentConstructor(): void
    {
        // Setup
        $expected = 'testText';

        // Test
        $content = new Content($expected);
        $actual = $content->getText();

        // Assert
        self::assertNotNull($content);
        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Content::getText
     * @covers \WindowsAzure\Common\Internal\Atom\Content::setText
     */
    public function testGetSetText(): void
    {
        // Setup
        $expected = 'testText';
        $content = new Content();

        // Test
        $content->setText($expected);
        $actual = $content->getText();

        // Assert
        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Content::getType
     * @covers \WindowsAzure\Common\Internal\Atom\Content::setType
     */
    public function testGetSetType(): void
    {
        // Setup
        $expected = 'text/plain';
        $content = new Content();

        // Test
        $content->setType($expected);
        $actual = $content->getType();

        // Assert
        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Content::writeXml
     */
    public function testWriteXml(): void
    {
        // Setup
        $expected = '<atom:content type="testType" xmlns:atom="http://www.w3.org/2005/Atom">testText</atom:content>';
        $expectedContentType = 'testType';
        $expectedText = 'testText';
        $content = new Content();

        // Test
        $content->setType($expectedContentType);
        $content->setText($expectedText);
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $content->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        // Assert
        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Content::parseXml
     */
    public function testParseXmlSuccess(): void
    {
        // Setup
        $expected = new Content();
        $xml = '<content key="value"/>';

        // Test
        $actual = new Content();
        $actual->parseXml($xml);

        // Assert
        self::assertEquals(
            $expected->getText(),
            $actual->getText()
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Content::parseXml
     */
    public function testWriteXmlSuccess(): void
    {
        // Setup
        $expected = '<atom:content xmlns:atom="http://www.w3.org/2005/Atom"/>';
        $content = new Content();

        // Test
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $content->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        // Assert
        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Content::getXml
     */
    public function testGetXml(): void
    {

        // Setup
        $xml = '<atom:content xmlns:atom="http://www.w3.org/2005/Atom"></atom:content>';
        $content = new Content();
        $content->parseXml($xml);

        // Test
        $result = $content->getXml();

        // Assert
        self::assertNotNull($result);
        self::assertInstanceOf(SimpleXMLElement::class, $result);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Content::fromXml
     */
    public function testFromXml(): void
    {
        // Setup
        $innerText = '<test>test string</test>';
        $xmlString = "<content>$innerText</content>";
        $atomContent = new Content();
        $xml = simplexml_load_string($xmlString);

        // Test
        $xml !== false ? $atomContent->fromXml($xml) : self::assertTrue(false, 'Failed creating the $xml variable');

        // Assert
        self::assertEquals($innerText, $atomContent->getText());
        self::assertEquals($xml, $atomContent->getXml());
    }
}
