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

namespace WindowsAzure\Common\Internal\Atom;

use DateTime;
use DateTimeInterface;
use Exception;
use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\Internal\Validate;
use XMLWriter;

/**
 * The feed class of ATOM library.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-vbus
 * @version   Release: 0.5.0_2016-11
 * @category  Microsoft
 */
class Feed extends AtomBase
{
    /**
     * The entry of the feed.
     *
     * @var array<int, Entry>
     */
    protected array $entry;

    /**
     * the author of the feed.
     *
     * @var ?array<int, Person>
     */
    protected ?array $author = null;

    /**
     * The category of the feed.
     *
     * @var ?array<int, Category>
     */
    protected ?array $category = null;

    /**
     * The contributor of the feed.
     *
     * @var ?array<int, Person>
     */
    protected ?array $contributor = null;

    /**
     * The generator of the feed.
     */
    protected ?Generator $generator = null;

    /**
     * The icon of the feed.
     */
    protected ?string $icon = null;

    /**
     * The ID of the feed.
     */
    protected ?string $id = null;

    /**
     * The link of the feed.
     *
     * @var ?array<int, AtomLink>
     */
    protected ?array $link = null;

    /**
     * The logo of the feed.
     */
    protected ?string $logo = null;

    /**
     * The rights of the feed.
     */
    protected ?string $rights = null;

    /**
     * The subtitle of the feed.
     */
    protected ?string $subtitle = null;

    /**
     * The title of the feed.
     */
    protected ?string $title = null;

    /**
     * The update of the feed.
     */
    protected ?DateTime $updated = null;

    /**
     * The extension element of the feed.
     */
    protected string $extensionElement;

    /**
     * Creates an ATOM FEED object with default parameters.
     */
    public function __construct()
    {
        $this->attributes = [];
    }

    /**
     * Creates a feed object with specified XML string.
     *
     * @param string $xmlString An XML string representing the feed object
     * @throws Exception
     */
    public function parseXml(string $xmlString): void
    {
        $feedXml = simplexml_load_string($xmlString);
        $attributes = $feedXml !== false ? $feedXml->attributes() : null;
        $feedArray = (array) $feedXml;
        if (! is_null($attributes)) {
            $this->attributes = (array) $attributes;
        }

        if (array_key_exists('author', $feedArray)) {
            $this->author = $this->processAuthorNode($feedArray);
        }

        if (array_key_exists('entry', $feedArray)) {
            $this->entry = $this->processEntryNode($feedArray);
        }

        if (array_key_exists('category', $feedArray)) {
            $this->category = $this->processCategoryNode($feedArray);
        }

        if (array_key_exists('contributor', $feedArray)) {
            $this->contributor = $this->processContributorNode($feedArray);
        }

        if (array_key_exists('generator', $feedArray)) {
            $generator = new Generator();
            $generatorValue = $feedArray['generator'];
            if (is_string($generatorValue)) {
                $generator->setText($generatorValue);
            } else {
                $generator->parseXml($generatorValue->asXML());
            }

            $this->generator = $generator;
        }

        if (array_key_exists('icon', $feedArray)) {
            $this->icon = (string) $feedArray['icon'];
        }

        if (array_key_exists('id', $feedArray)) {
            $this->id = (string) $feedArray['id'];
        }

        if (array_key_exists('link', $feedArray)) {
            $this->link = $this->processLinkNode($feedArray);
        }

        if (array_key_exists('logo', $feedArray)) {
            $this->logo = (string) $feedArray['logo'];
        }

        if (array_key_exists('rights', $feedArray)) {
            $this->rights = (string) $feedArray['rights'];
        }

        if (array_key_exists('subtitle', $feedArray)) {
            $this->subtitle = (string) $feedArray['subtitle'];
        }

        if (array_key_exists('title', $feedArray)) {
            $this->title = (string) $feedArray['title'];
        }

        if (array_key_exists('updated', $feedArray)) {
            $date = DateTime::createFromFormat(
                DateTimeInterface::ATOM,
                (string) $feedArray['updated']
            );

            $this->updated = $date !== false ? $date : null;
        }
    }

    /**
     * Adds an attribute to the feed object instance.
     *
     * @param string $attributeKey   The key of the attribute
     * @param mixed  $attributeValue The value of the attribute
     */
    public function addAttribute(string $attributeKey, $attributeValue): void
    {
        $this->attributes[$attributeKey] = $attributeValue;
    }

    /**
     * Gets the author of the feed.
     *
     * @return ?array<int, Person>
     */
    public function getAuthor(): ?array
    {
        return $this->author;
    }

    /**
     * Sets the author of the feed.
     *
     * @param array<int, Person> $author The author of the feed
     */
    public function setAuthor(array $author): void
    {
        $person = new Person();
        foreach ($author as $authorInstance) {
            Validate::isInstanceOf($authorInstance, $person, 'author');
        }
        $this->author = $author;
    }

    /**
     * Gets the category of the feed.
     *
     * @return ?array<int, Category>
     */
    public function getCategory(): ?array
    {
        return $this->category;
    }

    /**
     * Sets the category of the feed.
     *
     * @param array<int, Category> $category The category of the feed
     */
    public function setCategory(array $category): void
    {
        $categoryClassInstance = new Category();
        foreach ($category as $categoryInstance) {
            Validate::isInstanceOf(
                $categoryInstance,
                $categoryClassInstance,
                'category'
            );
        }
        $this->category = $category;
    }

    /**
     * Gets contributor.
     *
     * @return ?array<int, Person>
     */
    public function getContributor(): ?array
    {
        return $this->contributor;
    }

    /**
     * Sets contributor.
     *
     * @param array<int, Person> $contributor The contributor of the feed
     */
    public function setContributor(array $contributor): void
    {
        $person = new Person();
        foreach ($contributor as $contributorInstance) {
            Validate::isInstanceOf($contributorInstance, $person, 'contributor');
        }
        $this->contributor = $contributor;
    }

    /**
     * Gets generator.
     *
     * @return ?Generator
     */
    public function getGenerator(): ?Generator
    {
        return $this->generator;
    }

    /**
     * Sets the generator.
     *
     * @param Generator $generator Sets the generator of the feed
     */
    public function setGenerator(Generator $generator): void
    {
        $this->generator = $generator;
    }

    /**
     * Gets the icon of the feed.
     *
     * @return ?string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Sets the icon of the feed.
     *
     * @param string $icon The icon of the feed
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * Gets the ID of the feed.
     *
     * @return ?string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets the ID of the feed.
     *
     * @param string $id The ID of the feed
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the link of the feed.
     *
     * @return ?array<int, AtomLink>
     */
    public function getLink(): ?array
    {
        return $this->link;
    }

    /**
     * Sets the link of the feed.
     *
     * @param array<int, AtomLink> $link The link of the feed
     */
    public function setLink(array $link): void
    {
        $this->link = $link;
    }

    /**
     * Gets the logo of the feed.
     *
     * @return ?string
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * Sets the logo of the feed.
     *
     * @param string $logo The logo of the feed
     */
    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * Gets the rights of the feed.
     *
     * @return ?string
     */
    public function getRights(): ?string
    {
        return $this->rights;
    }

    /**
     * Sets the rights of the feed.
     *
     * @param string $rights The rights of the feed
     */
    public function setRights(string $rights): void
    {
        $this->rights = $rights;
    }

    /**
     * Gets the subtitle.
     *
     * @return ?string
     */
    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    /**
     * Sets the subtitle of the feed.
     *
     * @param string $subtitle Sets the subtitle of the feed
     */
    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Gets the title of the feed.
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the feed.
     *
     * @param string $title The title of the feed
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Gets the updated.
     *
     * @return ?DateTime
     */
    public function getUpdated(): ?DateTime
    {
        return $this->updated;
    }

    /**
     * Sets the updated.
     *
     * @param DateTime $updated updated
     */
    public function setUpdated(DateTime $updated): void
    {
        Validate::isInstanceOf($updated, new DateTime(), 'updated');
        $this->updated = $updated;
    }

    /**
     * Gets the extension element.
     *
     * @return string
     */
    public function getExtensionElement(): string
    {
        return $this->extensionElement;
    }

    /**
     * Sets the extension element.
     *
     * @param string $extensionElement The extension element
     */
    public function setExtensionElement(string $extensionElement): void
    {
        $this->extensionElement = $extensionElement;
    }

    /**
     * Gets the entry of the feed.
     *
     * @return array<int, Entry>
     */
    public function getEntry(): array
    {
        return $this->entry;
    }

    /**
     * Sets the entry of the feed.
     *
     * @param array<int, Entry> $entry The entry of the feed
     */
    public function setEntry(array $entry): void
    {
        $this->entry = $entry;
    }

    /**
     * Writes an XML representing the feed object.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');

        $xmlWriter->startElementNs('atom', 'feed', Resources::ATOM_NAMESPACE);
        $this->writeInnerXml($xmlWriter);
        $xmlWriter->endElement();
    }

    /**
     * Writes an XML representing the feed object.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeInnerXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');

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

        if (! is_null($this->contributor)) {
            $this->writeArrayItem(
                $xmlWriter,
                $this->contributor,
                Resources::CONTRIBUTOR
            );
        }

        if (! is_null($this->generator)) {
            $this->generator->writeXml($xmlWriter);
        }

        $this->writeOptionalElementNS(
            $xmlWriter,
            'atom',
            'icon',
            Resources::ATOM_NAMESPACE,
            $this->icon
        );

        $this->writeOptionalElementNS(
            $xmlWriter,
            'atom',
            'logo',
            Resources::ATOM_NAMESPACE,
            $this->logo
        );

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
            'rights',
            Resources::ATOM_NAMESPACE,
            $this->rights
        );

        $this->writeOptionalElementNS(
            $xmlWriter,
            'atom',
            'subtitle',
            Resources::ATOM_NAMESPACE,
            $this->subtitle
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
