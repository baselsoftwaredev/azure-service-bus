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


use Exception;
use PHPUnit\Framework\TestCase;
use WindowsAzure\Common\Internal\Atom\AtomLink;
use XMLWriter;

/**
 * Unit tests for class AtomLink.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-vbus
 * @version   Release: 0.5.0_2016-11
 * @category  Microsoft
 */
class AtomLinkTest extends TestCase
{
    /**
     */
    public function testAtomLinkConstructor(): void
    {
        $feed = new AtomLink();

        self::assertNotNull($feed);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::getUndefinedContent
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::setUndefinedContent
     */
    public function testGetSetUndefinedContent(): void
    {
        $expected = 'testUndefinedContent';
        $atomLink = new AtomLink();

        $atomLink->setUndefinedContent($expected);
        $actual = $atomLink->getUndefinedContent();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::getHref
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::setHref
     */
    public function testGetSetHref(): void
    {
        $expected = 'testHref';
        $atomLink = new AtomLink();

        $atomLink->setHref($expected);
        $actual = $atomLink->getHref();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::getRel
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::setRel
     */
    public function testGetSetRel(): void
    {
        $expected = 'testRel';
        $atomLink = new AtomLink();

        $atomLink->setRel($expected);
        $actual = $atomLink->getRel();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::getType
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::setType
     */
    public function testGetSetType(): void
    {
        $expected = 'testType';
        $atomLink = new AtomLink();

        $atomLink->setType($expected);
        $actual = $atomLink->getType();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::getHreflang
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::setHreflang
     */
    public function testGetSetHreflang(): void
    {
        $expected = 'testHreflang';
        $atomLink = new AtomLink();

        $atomLink->setHreflang($expected);
        $actual = $atomLink->getHreflang();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::getTitle
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::setTitle
     */
    public function testGetSetTitle(): void
    {
        $expected = 'testTitle';
        $atomLink = new AtomLink();

        $atomLink->setTitle($expected);
        $actual = $atomLink->getTitle();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::getLength
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::setLength
     */
    public function testGetSetLength(): void
    {
        $expected = 'testLength';
        $atomLink = new AtomLink();

        $atomLink->setLength($expected);
        $actual = $atomLink->getLength();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::parseXml
     * @throws Exception
     */
    public function testParseXmlSuccess(): void
    {
        $xml = '<link href="https://www.contonso.com"/>';
        $expected = new AtomLink();
        $expected->setHref('https://www.contonso.com');
        $actual = new AtomLink();

        $actual->parseXml($xml);

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\AtomLink::writeXml
     */
    public function testWriteXmlSuccess(): void
    {
        $expected = '<atom:link href="https://www.contonso.com" xmlns:atom="http://www.w3.org/2005/Atom"/>';
        $atomLink = new AtomLink();
        $atomLink->setHref('https://www.contonso.com');

        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $atomLink->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        self::assertEquals(
            $expected,
            $actual
        );
    }
}
