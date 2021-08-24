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
 * @link      https://github.com/WindowsAzure/azure-sdk-for-php
 * @category  Microsoft
 */

namespace WindowsAzure\Common\Internal\Atom;

use DateTime;
use DateTimeInterface;
use Exception;
use SimpleXMLElement;
use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\Internal\Validate;
use XMLWriter;

/**
 * The Entry class of ATOM standard.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/WindowsAzure/azure-sdk-for-php
 * @version   Release: 0.5.0_2016-11
 * @category  Microsoft
 */
class Entry extends AtomBase
{
    // @codingStandardsIgnoreStart

    /**
     * The author of the entry.
     *
     * @var Person[]|null
     */
    protected ?array $author = null;

    /**
     * The category of the entry.
     *
     * @var Category[]|null
     */
    protected ?array $category = null;

    /**
     * The content of the entry.
     */
    protected ?Content $content = null;

    /**
     * The contributor of the entry.
     *
     * @var Person[]|null
     */
    protected ?array $contributor = null;

    /**
     * An unique ID representing the entry.
     */
    protected ?string $id = null;

    /**
     * The link of the entry.
     *
     * @var AtomLink[]|null
     */
    protected ?array $link = null;

    /**
     * Is the entry published.
     */
    protected ?string $published = null;

    /**
     * The copy right of the entry.
     */
    protected ?string $rights = null;

    /**
     * The source of the entry.
     *
     * @var Source|string
     */
    protected $source;

    /**
     * The summary of the entry.
     */
    protected ?string $summary = null;

    /**
     * The title of the entry.
     */
    protected ?string $title = null;

    /**
     * Is the entry updated.
     */
    protected ?DateTime $updated = null;

    /**
     * The extension element of the entry.
     */
    protected string $extensionElement;

    /**
     * Populate the properties of an ATOM Entry instance with specified XML.
     *
     * @param string $xmlString A string representing an ATOM entry instance
     * @throws Exception
     */
    public function parseXml(string $xmlString): void
    {
        Validate::notNull($xmlString, 'xmlString');
        $xml = simplexml_load_string($xmlString);

        if ($xml !== false) {
            $this->fromXml($xml);
        }
    }

    /**
     * Creates an ATOM ENTRY instance with specified simpleXML object.
     *
     * @param SimpleXMLElement $entryXml xml element of ATOM ENTRY
     * @throws Exception
     */
    public function fromXml(SimpleXMLElement $entryXml): void
    {
        Validate::notNull($entryXml, 'entryXml');

        $this->attributes = (array) $entryXml->attributes();
        $entryArray = (array) $entryXml;

        if (array_key_exists(Resources::AUTHOR, $entryArray)) {
            $this->author = $this->processAuthorNode($entryArray);
        }

        if (array_key_exists(Resources::CATEGORY, $entryArray)) {
            $this->category = $this->processCategoryNode($entryArray);
        }

        if (array_key_exists('content', $entryArray)) {
            $content = new Content();
            $content->fromXml($entryArray['content']);
            $this->content = $content;
        }

        if (array_key_exists(Resources::CONTRIBUTOR, $entryArray)) {
            $this->contributor = $this->processContributorNode($entryArray);
        }

        if (array_key_exists('id', $entryArray)) {
            $this->id = (string) $entryArray['id'];
        }

        if (array_key_exists(Resources::LINK, $entryArray)) {
            $this->link = $this->processLinkNode($entryArray);
        }

        if (array_key_exists('published', $entryArray)) {
            $this->published = $entryArray['published'];
        }

        if (array_key_exists('rights', $entryArray)) {
            $this->rights = $entryArray['rights'];
        }

        if (array_key_exists('source', $entryArray)) {
            $source = new Source();
            $source->parseXml($entryArray['source']->asXML());
            $this->source = $source;
        }

        if (array_key_exists('title', $entryArray)) {
            $this->title = $entryArray['title'];
        }

        if (array_key_exists('updated', $entryArray)) {
            $date = DateTime::createFromFormat(
                DateTimeInterface::ATOM,
                (string) $entryArray['updated']
            );

            $this->updated = $date !== false ? $date : null;
        }
    }

    /**
     * Gets the author of the entry.
     *
     * @return Person[]|null
     */
    public function getAuthor(): ?array
    {
        return $this->author;
    }

    /**
     * Sets the author of the entry.
     *
     * @param Person[] $author The author of the entry
     */
    public function setAuthor(array $author): void
    {
        $this->author = $author;
    }

    /**
     * Gets the category.
     *
     * @return array<int, Category>|null
     */
    public function getCategory(): ?array
    {
        return $this->category;
    }

    /**
     * Sets the category.
     *
     * @param Category[] $category The category of the entry
     */
    public function setCategory(array $category): void
    {
        $this->category = $category;
    }

    /**
     * Gets the content.
     *
     * @return Content|null
     */
    public function getContent(): ?Content
    {
        return $this->content;
    }

    /**
     * Sets the content.
     *
     * @param Content $content Sets the content of the entry
     */
    public function setContent(Content $content): void
    {
        $this->content = $content;
    }

    /**
     * Gets the contributor.
     *
     * @return array<int, Person>|null
     */
    public function getContributor(): ?array
    {
        return $this->contributor;
    }

    /**
     * Sets the contributor.
     *
     * @param Person[] $contributor The contributor of the entry
     */
    public function setContributor(array $contributor): void
    {
        $this->contributor = $contributor;
    }

    /**
     * Gets the ID of the entry.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets the ID of the entry.
     *
     * @param string $id The id of the entry
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the link of the entry.
     *
     * @return AtomLink[]|null
     */
    public function getLink(): ?array
    {
        return $this->link;
    }

    /**
     * Sets the link of the entry.
     *
     * @param AtomLink[]|null $link The link of the entry
     */
    public function setLink(?array $link): void
    {
        $this->link = $link;
    }

    /**
     * Gets published of the entry.
     *
     * @return string|null
     */
    public function getPublished(): ?string
    {
        return $this->published;
    }

    /**
     * Sets published of the entry.
     *
     * @param string $published Is the entry published
     */
    public function setPublished(string $published): void
    {
        $this->published = $published;
    }

    /**
     * Gets the rights of the entry.
     *
     * @return string|null
     */
    public function getRights(): ?string
    {
        return $this->rights;
    }

    /**
     * Sets the rights of the entry.
     *
     * @param string $rights The rights of the entry
     */
    public function setRights(string $rights): void
    {
        $this->rights = $rights;
    }

    /**
     * Gets the source of the entry.
     *
     * @return Source|string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Sets the source of the entry.
     *
     * @param Source|string $source The source of the entry
     */
    public function setSource($source): void
    {
        $this->source = $source;
    }

    /**
     * Gets the summary of the entry.
     *
     * @return string|null
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * Sets the summary of the entry.
     *
     * @param string $summary The summary of the entry
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * Gets the title of the entry.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the entry.
     *
     * @param string $title The title of the entry
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Gets updated.
     *
     * @return DateTime|null
     */
    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    /**
     * Sets updated.
     *
     * @param DateTime $updated updated
     */
    public function setUpdated(DateTime $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * Gets extension element.
     *
     * @return string
     */
    public function getExtensionElement(): string
    {
        return $this->extensionElement;
    }

    /**
     * Sets extension element.
     *
     * @param string $extensionElement The extension element of the entry
     */
    public function setExtensionElement(string $extensionElement): void
    {
        $this->extensionElement = $extensionElement;
    }

    /**
     * Writes an inner XML string representing the entry.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');
        $xmlWriter->startElementNs(
            'atom',
            Resources::ENTRY,
            Resources::ATOM_NAMESPACE
        );
        $this->writeInnerXml($xmlWriter);
        $xmlWriter->endElement();
    }

    /**
     * Writes an inner XML string representing the entry.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeInnerXml(XMLWriter $xmlWriter): void
    {
        if (is_array($this->attributes)) {
            foreach (
                $this->attributes
                as $attributeName => $attributeValue
            ) {
                $xmlWriter->writeAttribute($attributeName, $attributeValue);
            }
        }

        if (! is_null($this->author)) {
            $this->writeArrayItem(
                $xmlWriter,
                $this->author,
                Resources::AUTHOR
            );
        }

        if (! is_null($this->category)) {
            $this->writeArrayItem(
                $xmlWriter,
                $this->category,
                Resources::CATEGORY
            );
        }

        if (! is_null($this->content)) {
            $this->content->writeXml($xmlWriter);
        }

        if (! is_null($this->contributor)) {
            $this->writeArrayItem(
                $xmlWriter,
                $this->contributor,
                Resources::CONTRIBUTOR
            );
        }

        $this->writeOptionalElementNS(
            $xmlWriter,
            'atom',
            'id',
            Resources::ATOM_NAMESPACE,
            $this->id
        );

        if (! is_null($this->link)) {
            $this->writeArrayItem(
                $xmlWriter,
                $this->link,
                Resources::LINK
            );
        }

        $this->writeOptionalElementNS(
            $xmlWriter,
            'atom',
            'published',
            Resources::ATOM_NAMESPACE,
            $this->published
        );

        $this->writeOptionalElementNS(
            $xmlWriter,
            'atom',
            'rights',
            Resources::ATOM_NAMESPACE,
            $this->rights
        );

        if (is_string($this->source)) {
            $this->writeOptionalElementNS(
                $xmlWriter,
                'atom',
                'source',
                Resources::ATOM_NAMESPACE,
                $this->source
            );
        }

        $this->writeOptionalElementNS(
            $xmlWriter,
            'atom',
            'summary',
            Resources::ATOM_NAMESPACE,
            $this->summary
        );

        $this->writeOptionalElementNS(
            $xmlWriter,
            'atom',
            'title',
            Resources::ATOM_NAMESPACE,
            $this->title
        );

        if (! is_null($this->updated)) {
            $xmlWriter->writeElementNs(
                'atom',
                'updated',
                Resources::ATOM_NAMESPACE,
                $this->updated->format(DateTimeInterface::ATOM)
            );
        }
    }
}
