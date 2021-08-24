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
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @category  Microsoft
 */

namespace Tests\unit\WindowsAzure\Common\Internal\Atom;

use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use WindowsAzure\Common\Internal\Atom\AtomLink;
use WindowsAzure\Common\Internal\Atom\Category;
use WindowsAzure\Common\Internal\Atom\Generator;
use WindowsAzure\Common\Internal\Atom\Person;
use WindowsAzure\Common\Internal\Atom\Source;
use XMLWriter;

/**
 * Unit tests for class Source.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   Release: 0.5.0_2016-11
 * @category  Microsoft
 */
class SourceTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::__construct
     */
    public function testSourceConstructor(): void
    {
        $feed = new Source();

        self::assertNotNull($feed);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getAttributes
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setAttributes
     */
    public function testGetSetAttributes(): void
    {
        $expected = [];
        $expected['attributeKey'] = 'attributeValue';
        $source = new Source();

        $source->setAttributes($expected);
        $actual = $source->getAttributes();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getAuthor
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setAuthor
     */
    public function testGetSetAuthor(): void
    {
        $person = new Person();
        $person->setName('testAuthor');
        $expected = [$person];
        $source = new Source();

        $source->setAuthor($expected);
        $actual = $source->getAuthor();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getCategory
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setCategory
     */
    public function testGetSetCategory(): void
    {
        $expected = [];
        $category = new Category();
        $category->setTerm('testTerm');
        $expected[] = $category;
        $source = new Source();

        $source->setCategory($expected);
        $actual = $source->getCategory();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getContributor
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setContributor
     */
    public function testGetSetContributor(): void
    {
        $contributor = new Person();
        $contributor->setName('testContributor');
        $expected = [$contributor];
        $source = new Source();

        $source->setContributor($expected);
        $actual = $source->getContributor();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getGenerator
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setGenerator
     */
    public function testGetSetGenerator(): void
    {
        $expected = new Generator();
        $source = new Source();

        $source->setGenerator($expected);
        $actual = $source->getGenerator();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getIcon
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setIcon
     */
    public function testGetSetIcon(): void
    {
        $expected = 'testIcon';
        $source = new Source();

        $source->setIcon($expected);
        $actual = $source->getIcon();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getId
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setId
     */
    public function testGetSetId(): void
    {
        $expected = 'testId';
        $source = new Source();

        $source->setId($expected);
        $actual = $source->getId();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getLink
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setLink
     */
    public function testGetSetLink(): void
    {
        $atomLink = new AtomLink();
        $atomLink->setTitle('testLink');
        $expected = [$atomLink];
        $source = new Source();

        $source->setLink($expected);
        $actual = $source->getLink();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getLogo
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setLogo
     */
    public function testGetSetLogo(): void
    {
        $expected = 'testLogo';
        $source = new Source();

        $source->setLogo($expected);
        $actual = $source->getLogo();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getRights
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setRights
     */
    public function testGetSetRights(): void
    {
        $expected = 'testRights';
        $source = new Source();

        $source->setRights($expected);
        $actual = $source->getRights();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getSubtitle
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setSubtitle
     */
    public function testGetSetSubtitle(): void
    {
        $expected = 'testSubtitle';
        $source = new Source();

        $source->setSubtitle($expected);
        $actual = $source->getSubtitle();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getTitle
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setTitle
     */
    public function testGetSetTitle(): void
    {
        $expected = 'testTitle';
        $source = new Source();

        $source->setTitle($expected);
        $actual = $source->getTitle();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getUpdated
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setUpdated
     */
    public function testGetSetUpdated(): void
    {
        $expected = new DateTime();
        $source = new Source();

        $source->setUpdated($expected);
        $actual = $source->getUpdated();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::getExtensionElement
     * @covers \WindowsAzure\Common\Internal\Atom\Source::setExtensionElement
     */
    public function testGetSetExtensionElement(): void
    {
        $expected = 'testExtensionElement';
        $source = new Source();

        $source->setExtensionElement($expected);
        $actual = $source->getExtensionElement();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::writeXml
     * @covers \WindowsAzure\Common\Internal\Atom\Source::writeInnerXml
     */
    public function testSourceWriteXmlWorks(): void
    {
        $expected = '<atom:source xmlns:atom="http://www.w3.org/2005/Atom"/>';
        $source = new Source();

        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $source->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Source::writeXml
     * @covers \WindowsAzure\Common\Internal\Atom\Source::writeInnerXml
     */
    public function testSourceWriteXmlAllPropertiesWorks(): void
    {
        $expected = '<atom:source xmlns:atom="http://www.w3.org/2005/Atom"><atom:author xmlns:atom="http://www.w3.org/2005/Atom"><atom:name xmlns:atom="http://www.w3.org/2005/Atom"></atom:name></atom:author><atom:category xmlns:atom="http://www.w3.org/2005/Atom"></atom:category><atom:contributor xmlns:atom="http://www.w3.org/2005/Atom"><atom:name xmlns:atom="http://www.w3.org/2005/Atom"></atom:name></atom:contributor><atom:category xmlns:atom="http://www.w3.org/2005/Atom"></atom:category><atom:icon xmlns:atom="http://www.w3.org/2005/Atom">testIcon</atom:icon><atom:logo xmlns:atom="http://www.w3.org/2005/Atom">testLogo</atom:logo><atom:id xmlns:atom="http://www.w3.org/2005/Atom">testId</atom:id><atom:link xmlns:atom="http://www.w3.org/2005/Atom"/><atom:rights xmlns:atom="http://www.w3.org/2005/Atom">testRights</atom:rights><atom:subtitle xmlns:atom="http://www.w3.org/2005/Atom">testSubtitle</atom:subtitle><atom:title xmlns:atom="http://www.w3.org/2005/Atom">testTitle</atom:title><atom:updated xmlns:atom="http://www.w3.org/2005/Atom">2012-06-17T20:53:36-07:00</atom:updated></atom:source>';
        $source = new Source();
        $author = [];
        $authorInstance = new Person();
        $author[] = $authorInstance;

        $category = [];
        $categoryInstance = new Category();
        $category[] = $categoryInstance;

        $contributor = [];
        $contributorInstance = new Person();
        $contributor[] = $contributorInstance;

        $link = [];
        $linkInstance = new AtomLink();
        $link[] = $linkInstance;

        $source->setAuthor($author);
        $source->setCategory($category);
        $source->setContributor($contributor);
        $source->setGenerator(new Generator());
        $source->setIcon('testIcon');
        $source->setId('testId');
        $source->setLink($link);
        $source->setLogo('testLogo');
        $source->setRights('testRights');
        $source->setSubtitle('testSubtitle');
        $source->setTitle('testTitle');
        $date = DateTime::createFromFormat(DateTimeInterface::ATOM, '2012-06-17T20:53:36-07:00');
        $date !== false ? $source->setUpdated($date) : self::assertTrue(false, 'Failed creating DateTime object');

        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $source->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        self::assertEquals(
            $expected,
            $actual
        );
    }
}
