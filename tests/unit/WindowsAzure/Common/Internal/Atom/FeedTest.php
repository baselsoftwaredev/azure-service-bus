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

use DateTime;
use DateTimeInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use WindowsAzure\Common\Internal\Atom\AtomLink;
use WindowsAzure\Common\Internal\Atom\Category;
use WindowsAzure\Common\Internal\Atom\Entry;
use WindowsAzure\Common\Internal\Atom\Feed;
use WindowsAzure\Common\Internal\Atom\Generator;
use WindowsAzure\Common\Internal\Atom\Person;
use XMLWriter;

/**
 * Unit tests for class Feed.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class FeedTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::__construct
     */
    public function testFeedConstructor(): void
    {
        $feed = new Feed();

        $this->assertNotNull($feed);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getAttributes
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setAttributes
     */
    public function testGetSetAttributes(): void
    {
        $expected = [];
        $expected['key'] = 'value';
        $feed = new Feed();

        $feed->setAttributes($expected);
        $actual = $feed->getAttributes();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getEntry
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setEntry
     */
    public function testGetSetEntry(): void
    {
        $expected = new Entry();
        $expected->setTitle('testEntry');
        $feed = new Feed();

        $feed->setEntry([$expected]);
        $actual = $feed->getEntry();

        self::assertEquals(
            [$expected],
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getCategory
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setCategory
     */
    public function testGetSetCategory(): void
    {
        $expected = [];
        $expected[] = new Category();
        $feed = new Feed();

        $feed->setCategory($expected);
        $actual = $feed->getCategory();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getContributor
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setContributor
     */
    public function testGetSetContributor(): void
    {
        $expected = [];
        $expected[] = new Person();
        $feed = new Feed();

        $feed->setContributor($expected);
        $actual = $feed->getContributor();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getGenerator
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setGenerator
     */
    public function testGetSetGenerator(): void
    {
        $expected = new Generator();
        $feed = new Feed();

        $feed->setGenerator($expected);
        $actual = $feed->getGenerator();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getIcon
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setIcon
     */
    public function testGetSetIcon(): void
    {
        $expected = 'testIcon';
        $feed = new Feed();

        $feed->setIcon($expected);
        $actual = $feed->getIcon();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getId
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setId
     */
    public function testGetSetId(): void
    {
        $expected = 'testId';
        $feed = new Feed();

        $feed->setId($expected);
        $actual = $feed->getId();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getLink
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setLink
     */
    public function testGetSetLink(): void
    {
        $expected = [];
        $expected[] = new AtomLink();
        $feed = new Feed();

        $feed->setLink($expected);
        $actual = $feed->getLink();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getLogo
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setLogo
     */
    public function testGetSetLogo(): void
    {
        $expected = 'testLogo';
        $feed = new Feed();

        $feed->setLogo($expected);
        $actual = $feed->getLogo();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getRights
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setRights
     */
    public function testGetSetRights(): void
    {
        $expected = 'testRights';
        $feed = new Feed();

        $feed->setRights($expected);
        $actual = $feed->getRights();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getSubtitle
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setSubtitle
     */
    public function testGetSetSubtitle(): void
    {
        $expected = 'testSubtitle';
        $feed = new Feed();

        $feed->setSubtitle($expected);
        $actual = $feed->getSubtitle();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getTitle
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setTitle
     */
    public function testGetSetTitle(): void
    {
        $expected = 'testTitle';
        $feed = new Feed();

        $feed->setTitle($expected);
        $actual = $feed->getTitle();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getUpdated
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setUpdated
     */
    public function testGetSetUpdated(): void
    {
        $expected = new DateTime();
        $feed = new Feed();

        $feed->setUpdated($expected);
        $actual = $feed->getUpdated();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::getExtensionElement
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::setExtensionElement
     */
    public function testGetSetExtensionElement(): void
    {
        $expected = 'testExtensionElement';
        $feed = new Feed();

        $feed->setExtensionElement($expected);
        $actual = $feed->getExtensionElement();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::writeXml
     */
    public function testWriteXmlWorks(): void
    {
        $expected = '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom"/>';
        $feed = new Feed();

        // Test 
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $feed->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::writeXml
     */
    public function testWriteXmlWorksWithNamespace(): void
    {
        // Setup 
        $expected = '<atom:feed xmlns:atom="http://www.w3.org/2005/Atom"/>';
        $feed = new Feed();

        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $feed->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::parseXml
     * @throws Exception
     */
    public function testParseXmlSuccess(): void
    {
        $expected = new Feed();
        $actual = new Feed();
        $xml = '<feed></feed>';

        $actual->parseXml($xml);

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::parseXml
     * @throws Exception
     */
    public function testFeedParseXmlMultipleLinks(): void
    {
        $expected = new Feed();
        $link = [];
        $linkInstanceOne = new AtomLink();
        $linkInstanceOne->setHref('https://linkone.com');
        $linkInstanceTwo = new AtomLink();
        $linkInstanceTwo->setHref('https://linktwo.com');
        $link[] = $linkInstanceOne;
        $link[] = $linkInstanceTwo;
        $expected->setLink($link);
        $xml = '<feed xmlns="http://www.w3.org/2005/Atom"><link href="https://linkone.com"/><link href="https://linktwo.com"/></feed>';

        $actual = new Feed();
        $actual->parseXml($xml);


        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Feed::parseXml
     * @throws Exception
     */
    public function testFeedParseXmlAllProperties(): void
    {
        $expected = new Feed();
        $entry = [];
        $entry[] = new Entry();
        $category = [];
        $categoryInstance = new Category();
        $categoryInstance->setScheme('testCategory');
        $category[] = $categoryInstance;
        $contributor = [];
        $contributorItem = new Person();
        $contributorItem->setName('testContributor');
        $contributor[] = $contributorItem;
        $generator = new Generator();
        $generator->setText('testGenerator');
        $icon = 'testIcon';
        $id = 'testId';
        $link = [];
        $atomLink = new AtomLink();
        $atomLink->setHref('https://linkone.com');
        $link[] = $atomLink;
        $logo = 'testLogo';
        $rights = 'testRights';
        $subtitle = 'testSubtitle';
        $title = 'testTitle';
        $updated = DateTime::createFromFormat(DateTimeInterface::ATOM, '2011-09-29T23:50:26+00:00');

        $expected->setEntry($entry);
        $expected->setCategory($category);
        $expected->setContributor($contributor);
        $expected->setGenerator($generator);
        $expected->setIcon($icon);
        $expected->setId($id);
        $expected->setLink($link);
        $expected->setLogo($logo);
        $expected->setRights($rights);
        $expected->setSubtitle($subtitle);
        $expected->setTitle($title);
        $updated !== false ? $expected->setUpdated($updated) : self::assertTrue(false, 'Failed creating date object');

        $actual = new Feed();

        $xml = '
        <feed xmlns="http://www.w3.org/2005/Atom">
            <entry/>
            <content/>
            <category scheme="testCategory"/>
            <contributor>testContributor</contributor>
            <generator>testGenerator</generator>
            <icon>testIcon</icon>
            <id>testId</id>
            <link href="https://linkone.com"/>
            <logo>testLogo</logo>
            <rights>testRights</rights>
            <subtitle>testSubtitle</subtitle>
            <title>testTitle</title>
            <updated>2011-09-29T23:50:26+00:00</updated>
        </feed>';

        $actual->parseXml($xml);

        self::assertEquals(
            $expected,
            $actual
        );
    }
}
