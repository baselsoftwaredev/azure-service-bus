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
use Exception;
use PHPUnit\Framework\TestCase;
use WindowsAzure\Common\Internal\Atom\AtomLink;
use WindowsAzure\Common\Internal\Atom\Category;
use WindowsAzure\Common\Internal\Atom\Content;
use WindowsAzure\Common\Internal\Atom\Entry;
use WindowsAzure\Common\Internal\Atom\Person;
use WindowsAzure\Common\Internal\Atom\Source;
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
class EntryTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::__construct
     */
    public function testEntryConstructor(): void
    {
        $entry = new Entry();

        self::assertNotNull($entry);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getAuthor
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setAuthor
     */
    public function testEntryGetSetAuthor(): void
    {
        $expected = new Person();
        $expected->setName('testPerson');
        $entry = new Entry();

        $entry->setAuthor([$expected]);
        $actual = $entry->getAuthor() !== null ? $entry->getAuthor()[0]->getName() : false;

        self::assertEquals(
            $expected->getName(),
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getCategory
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setCategory
     */
    public function testEntryGetSetCategory(): void
    {
        $expected = new Category();
        $expected->setTerm('testTerm');
        $entry = new Entry();

        $entry->setCategory([$expected]);
        $actual = $entry->getCategory() !== null ? $entry->getCategory()[0]->getTerm() : false;

        self::assertEquals(
            $expected->getTerm(),
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getContent
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setContent
     */
    public function testEntryGetSetContent(): void
    {
        $expected = new Content();
        $expected->setText('testText');
        $entry = new Entry();

        $entry->setContent($expected);
        $actual = $entry->getContent() !== null ? $entry->getContent()->getText() : false;

        self::assertEquals(
            $expected->getText(),
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getContributor
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setContributor
     */
    public function testEntryGetSetContributor(): void
    {
        $expected = new Person();
        $expected->setName('testContributor');
        $entry = new Entry();

        $entry->setContributor([$expected]);
        $actual = $entry->getContributor() !== null ? $entry->getContributor()[0]->getName() : false;

        self::assertEquals(
            $expected->getName(),
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getId
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setId
     */
    public function testEntryGetSetId(): void
    {
        $expected = 'testId';
        $entry = new Entry();

        $entry->setId($expected);
        $actual = $entry->getId();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getLink
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setLink
     */
    public function testEntryGetSetLink(): void
    {
        $expected = new AtomLink();
        $entry = new Entry();

        $entry->setLink([$expected]);
        $actual = $entry->getLink();

        self::assertEquals(
            [$expected],
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getPublished
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setPublished
     */
    public function testEntryGetSetPublished(): void
    {
        $expected = 'true';
        $entry = new Entry();

        $entry->setPublished($expected);
        $actual = $entry->getPublished();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getRights
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setRights
     */
    public function testEntryGetSetRights(): void
    {
        $expected = 'rights';
        $entry = new Entry();

        $entry->setRights($expected);
        $actual = $entry->getRights();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getSource
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setSource
     */
    public function testEntryGetSetSource(): void
    {
        $expected = new Source();
        $entry = new Entry();

        $entry->setSource($expected);
        $actual = $entry->getSource();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getSummary
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setSummary
     */
    public function testEntryGetSetSummary(): void
    {
        $expected = 'testSummary';
        $entry = new Entry();

        $entry->setSummary($expected);
        $actual = $entry->getSummary();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getTitle
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setTitle
     */
    public function testEntryGetSetTitle(): void
    {
        $expected = 'testTitle';
        $entry = new Entry();

        $entry->setTitle($expected);
        $actual = $entry->getTitle();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getUpdated
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setUpdated
     */
    public function testEntryGetSetUpdated(): void
    {
        $expected = new DateTime();
        $entry = new Entry();

        $entry->setUpdated($expected);
        $actual = $entry->getUpdated();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getExtensionElement
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setExtensionElement
     */
    public function testEntryGetSetExtensionElement(): void
    {
        $expected = 'testExtensionElement';
        $entry = new Entry();

        $entry->setExtensionElement($expected);
        $actual = $entry->getExtensionElement();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::writeXml
     */
    public function testEntryToXml(): void
    {
        $entry = new Entry();
        $expected = '<atom:entry xmlns:atom="http://www.w3.org/2005/Atom"/>';

        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $entry->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getAttributes
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setAttributes
     */
    public function testGetSetAttributes(): void
    {
        $expected = [];
        $expected['testKey'] = 'testValue';
        $entry = new Entry();

        $entry->setAttributes($expected);
        $actual = $entry->getAttributes();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getAuthor
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setAuthor
     */
    public function testGetSetAuthor(): void
    {
        $expected = new Person('testAuthor');
        $entry = new Entry();

        $entry->setAuthor([$expected]);
        $actual = $entry->getAuthor() !== null ? $entry->getAuthor()[0] : false;

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getCategory
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setCategory
     */
    public function testGetSetCategory(): void
    {
        $expected = 'testCategory';
        $entry = new Entry();

        $entry->setCategory([new Category($expected)]);
        $actual = $entry->getCategory() !== null ? $entry->getCategory()[0]->getUndefinedContent() : false;

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getContent
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setContent
     */
    public function testGetSetContent(): void
    {
        $expected = new Content('testContent');
        $entry = new Entry();

        $entry->setContent($expected);
        $actual = $entry->getContent();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getContributor
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setContributor
     */
    public function testGetSetContributor(): void
    {
        $expected = [new Person()];
        $entry = new Entry();

        $entry->setContributor($expected);
        $actual = $entry->getContributor();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getId
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setId
     */
    public function testGetSetId(): void
    {
        $expected = 'testId';
        $entry = new Entry();

        $entry->setId($expected);
        $actual = $entry->getId();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getLink
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setLink
     */
    public function testGetSetLink(): void
    {
        $expected = new AtomLink();
        $expected->setHref('testLink');
        $entry = new Entry();

        $entry->setLink([$expected]);
        $actual = $entry->getLink();

        self::assertEquals(
            [$expected],
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getPublished
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setPublished
     */
    public function testGetSetPublished(): void
    {
        $expected = 'testPublished';
        $entry = new Entry();

        $entry->setPublished($expected);
        $actual = $entry->getPublished();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getRights
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setRights
     */
    public function testGetSetRights(): void
    {
        $expected = 'testRights';
        $entry = new Entry();

        $entry->setRights($expected);
        $actual = $entry->getRights();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getSource
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setSource
     */
    public function testGetSetSource(): void
    {
        $expected = 'testSource';
        $entry = new Entry();

        $entry->setSource($expected);
        $actual = $entry->getSource();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getSummary
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setSummary
     */
    public function testGetSetSummary(): void
    {
        $expected = 'testSummary';
        $entry = new Entry();

        $entry->setSummary($expected);
        $actual = $entry->getSummary();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getTitle
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setTitle
     */
    public function testGetSetTitle(): void
    {
        $expected = 'testTitle';
        $entry = new Entry();

        $entry->setTitle($expected);
        $actual = $entry->getTitle();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getUpdated
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setUpdated
     */
    public function testGetSetUpdated(): void
    {
        $expected = new DateTime('now');
        $entry = new Entry();

        $entry->setUpdated($expected);
        $actual = $entry->getUpdated();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::getExtensionElement
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::setExtensionElement
     */
    public function testGetSetExtensionElement(): void
    {
        $expected = 'testExtensionElement';
        $entry = new Entry();

        $entry->setExtensionElement($expected);
        $actual = $entry->getExtensionElement();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Entry::fromXml
     * @throws Exception
     */
    public function testFromXml(): void
    {

        $xmlString = '<entry>
                       <content>
                       </content>
                      </entry>';
        $entry = new Entry();
        $xml = simplexml_load_string($xmlString);

        $entry->fromXml($xml);

        $this->assertNotNull($entry->getContent());
    }
}
