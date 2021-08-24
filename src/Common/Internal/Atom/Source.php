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
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
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
 * The source class of ATOM library.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/WindowsAzure/azure-sdk-for-php
 * @version   Release: 0.5.0_2016-11
 * @category  Microsoft
 */
class Source extends AtomBase
{
    // @codingStandardsIgnoreStart

    /**
     * The author the source.
     *
     * @var array<int, Person>
     */
    protected array $author;

    /**
     * The category of the source.
     *
     * @var array<int, Category>
     */
    protected array $category;

    /**
     * The contributor of the source.
     *
     * @var array<int, Person>
     */
    protected array $contributor;

    /**
     * The generator of the source.
     */
    protected ?Generator $generator = null;

    /**
     * The icon of the source.
     */
    protected ?string $icon = null;

    /**
     * The ID of the source.
     */
    protected ?string $id = null;

    /**
     * The link of the source.
     *
     * @var array<int, AtomLink>
     */
    protected ?array $link = null;

    /**
     * The logo of the source.
     */
    protected ?string $logo = null;

    /**
     * The rights of the source.
     */
    protected ?string $rights = null;

    /**
     * The subtitle of the source.
     */
    protected ?string $subtitle = null;

    /**
     * The title of the source.
     */
    protected ?string $title = null;

    /**
     * The update of the source.
     */
    protected ?DateTime $updated = null;

    /**
     * The extension element of the source.
     */
    protected string $extensionElement;

    /**
     * Creates an ATOM FEED object with default parameters.
     */
    public function __construct()
    {
        $this->attributes = [];
        $this->category = [];
        $this->contributor = [];
        $this->author = [];
    }

    /**
     * Creates a source object with specified XML string.
     *
     * @param string $xmlString The XML string representing a source
     * @throws Exception
     */
    public function parseXml(string $xmlString): void
    {
        $sourceXml = new SimpleXMLElement($xmlString);
        $sourceArray = (array) $sourceXml;

        if (array_key_exists(Resources::AUTHOR, $sourceArray)) {
            $this->author = $this->processAuthorNode($sourceArray);
        }

        if (array_key_exists(Resources::CATEGORY, $sourceArray)) {
            $this->category = $this->processCategoryNode($sourceArray);
        }

        if (array_key_exists(Resources::CONTRIBUTOR, $sourceArray)) {
            $this->contributor = $this->processContributorNode($sourceArray);
        }

        if (array_key_exists('generator', $sourceArray)) {
            $generator = new Generator();
            $generator->setText((string) $sourceArray['generator']->asXML());
            $this->generator = $generator;
        }

        if (array_key_exists('icon', $sourceArray)) {
            $this->icon = (string) $sourceArray['icon'];
        }

        if (array_key_exists('id', $sourceArray)) {
            $this->id = (string) $sourceArray['id'];
        }

        if (array_key_exists(Resources::LINK, $sourceArray)) {
            $this->link = $this->processLinkNode($sourceArray);
        }

        if (array_key_exists('logo', $sourceArray)) {
            $this->logo = (string) $sourceArray['logo'];
        }

        if (array_key_exists('rights', $sourceArray)) {
            $this->rights = (string) $sourceArray['rights'];
        }

        if (array_key_exists('subtitle', $sourceArray)) {
            $this->subtitle = (string) $sourceArray['subtitle'];
        }

        if (array_key_exists('title', $sourceArray)) {
            $this->title = (string) $sourceArray['title'];
        }

        if (array_key_exists('updated', $sourceArray)) {
            $date = DateTime::createFromFormat(
                DateTimeInterface::ATOM,
                (string) $sourceArray['updated']
            );

            $this->updated = $date !== false ? $date : null;
        }
    }

    /**
     * Gets the author of the source.
     *
     * @return array<int, Person>
     */
    public function getAuthor(): array
    {
        return $this->author;
    }

    /**
     * Sets the author of the source.
     *
     * @param array<int, Person> $author An array of authors of the sources
     */
    public function setAuthor(array $author): void
    {
        $this->author = $author;
    }

    /**
     * Gets the category of the source.
     *
     * @return array<int, Category>
     */
    public function getCategory(): array
    {
        return $this->category;
    }

    /**
     * Sets the category of the source.
     *
     * @param array<int, Category> $category The category of the source
     */
    public function setCategory(array $category): void
    {
        $this->category = $category;
    }

    /**
     * Gets contributor.
     *
     * @return array<int, Person>
     */
    public function getContributor(): array
    {
        return $this->contributor;
    }

    /**
     * Sets contributor.
     *
     * @param array<int, Person> $contributor The contributors of the source
     */
    public function setContributor(array $contributor): void
    {
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
     * @param Generator $generator Sets the generator of the source
     */
    public function setGenerator(Generator $generator): void
    {
        $this->generator = $generator;
    }

    /**
     * Gets the icon of the source.
     *
     * @return ?string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Sets the icon of the source.
     *
     * @param string $icon The icon of the source
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * Gets the ID of the source.
     *
     * @return ?string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Sets the ID of the source.
     *
     * @param string $id The ID of the source
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the link of the source.
     *
     * @return ?array<int, AtomLink>
     */
    public function getLink(): ?array
    {
        return $this->link;
    }

    /**
     * Sets the link of the source.
     *
     * @param array<int, AtomLink> $link The link of the source
     */
    public function setLink(array $link): void
    {
        $this->link = $link;
    }

    /**
     * Gets the logo of the source.
     *
     * @return ?string
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * Sets the logo of the source.
     *
     * @param string $logo The logo of the source
     */
    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * Gets the rights of the source.
     *
     * @return ?string
     */
    public function getRights(): ?string
    {
        return $this->rights;
    }

    /**
     * Sets the rights of the source.
     *
     * @param string $rights The rights of the source
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
     * Sets the subtitle of the source.
     *
     * @param string $subtitle Sets the subtitle of the source
     */
    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Gets the title of the source.
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the source.
     *
     * @param string $title The title of the source
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
     * Writes an XML representing the source object.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');
        $xmlWriter->startElementNs(
            'atom',
            'source',
            Resources::ATOM_NAMESPACE
        );
        $this->writeInnerXml($xmlWriter);
        $xmlWriter->endElement();
    }

    /**
     * Writes an inner XML representing the source object.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeInnerXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');
        if (is_array($this->attributes)) {
            foreach ($this->attributes as $attributeName => $attributeValue) {
                $xmlWriter->writeAttribute($attributeName, $attributeValue);
            }
        }

        Validate::isArray($this->author, Resources::AUTHOR);
        $this->writeArrayItem($xmlWriter, $this->author, Resources::AUTHOR);

        Validate::isArray($this->category, Resources::CATEGORY);
        $this->writeArrayItem(
            $xmlWriter,
            $this->category,
            Resources::CATEGORY
        );

        Validate::isArray($this->contributor, Resources::CONTRIBUTOR);
        $this->writeArrayItem(
            $xmlWriter,
            $this->contributor,
            Resources::CONTRIBUTOR
        );

        if (! is_null($this->generator)) {
            $this->generator->writeXml($xmlWriter);
        }

        if (! is_null($this->icon)) {
            $xmlWriter->writeElementNs(
                'atom',
                'icon',
                Resources::ATOM_NAMESPACE,
                $this->icon
            );
        }

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
            Validate::isArray($this->link, Resources::LINK);
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
