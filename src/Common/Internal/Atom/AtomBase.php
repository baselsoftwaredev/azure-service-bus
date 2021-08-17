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

use SimpleXMLElement;
use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\Internal\Validate;
use XMLWriter;

/**
 * The base class of ATOM library.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class AtomBase
{
    /**
     * The attributes of the feed.
     *
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * Gets the attributes of the ATOM class.
     *
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Sets the attributes of the ATOM class.
     *
     * @param array<string, mixed> $attributes The attributes of the array
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * Sets an attribute to the ATOM object instance.
     *
     * @param string $attributeKey   The key of the attribute
     * @param mixed  $attributeValue The value of the attribute
     */
    public function setAttribute(string $attributeKey, $attributeValue): void
    {
        $this->attributes[$attributeKey] = $attributeValue;
    }

    /**
     * Gets an attribute with a specified attribute key.
     *
     * @param string $attributeKey The key of the attribute
     * @return mixed
     */
    public function getAttribute(string $attributeKey)
    {
        return $this->attributes[$attributeKey];
    }

    /**
     * Processes author node.
     *
     * @param XMLWriter $xmlWriter   The XML writer
     * @param array<int, AtomLink|Category|Content|Entry|Feed|Person|Source>     $itemArray   An array of item to write
     * @param string    $elementName The name of the element
     */
    protected function writeArrayItem(XMLWriter $xmlWriter, array $itemArray, string $elementName): void
    {
        Validate::isString($elementName, 'elementName');

        foreach ($itemArray as $itemInstance) {
            $xmlWriter->startElementNs(
                'atom',
                $elementName,
                Resources::ATOM_NAMESPACE
            );
            $itemInstance->writeInnerXml($xmlWriter);
            $xmlWriter->endElement();
        }
    }

    /**
     * Processes author node.
     *
     * @param array<string, array|SimpleXMLElement> $xmlArray An array of simple xml elements
     * @return Person[]
     */
    protected function processAuthorNode(array $xmlArray): array
    {
        $author = [];
        $authorItem = $xmlArray[Resources::AUTHOR];

        if (is_array($authorItem)) {
            foreach ($xmlArray[Resources::AUTHOR] as $authorXmlInstance) {
                $authorInstance = new Person();
                if (is_string($authorXmlInstance->asXML())) {
                    $authorInstance->parseXml($authorXmlInstance->asXML());
                    $author[] = $authorInstance;
                }
            }
        } else {
            $authorInstance = new Person();
            if (is_string($authorItem->asXML())) {
                $authorInstance->parseXml($authorItem->asXML());
                $author[] = $authorInstance;
            }
        }

        return $author;
    }

    /**
     * Processes entry node.
     *
     * @param array<string, array|SimpleXMLElement> $xmlArray An array of simple xml elements
     * @return array<int, Entry>
     */
    protected function processEntryNode(array $xmlArray): array
    {
        $entry = [];
        $entryItem = $xmlArray[Resources::ENTRY];

        if (is_array($entryItem)) {
            foreach ($xmlArray[Resources::ENTRY] as $entryXmlInstance) {
                $entryInstance = new Entry();
                $entryInstance->fromXml($entryXmlInstance);
                $entry[] = $entryInstance;
            }
        } else {
            $entryInstance = new Entry();
            $entryInstance->fromXml($entryItem);
            $entry[] = $entryInstance;
        }

        return $entry;
    }

    /**
     * Processes category node.
     *
     * @param array<string, array|SimpleXMLElement> $xmlArray An array of simple xml elements
     * @return Category[]
     */
    protected function processCategoryNode(array $xmlArray): array
    {
        $category = [];
        $categoryItem = $xmlArray[Resources::CATEGORY];

        if (is_array($categoryItem)) {
            foreach ($xmlArray[Resources::CATEGORY] as $categoryXmlInstance) {
                $categoryInstance = new Category();
                $categoryInstance->parseXml($categoryXmlInstance->asXML());
                $category[] = $categoryInstance;
            }
        } else {
            $categoryInstance = new Category();
            if (is_string($categoryItem->asXML())) {
                $categoryInstance->parseXml($categoryItem->asXML());
                $category[] = $categoryInstance;
            }
        }

        return $category;
    }

    /**
     * Processes contributor node.
     *
     * @param array<string, array|string|SimpleXMLElement> $xmlArray An array of simple xml elements
     * @return Person[]
     */
    protected function processContributorNode(array $xmlArray): array
    {
        $contributor = [];
        $contributorItem = $xmlArray[Resources::CONTRIBUTOR];

        if (is_array($xmlArray[Resources::CONTRIBUTOR])) {
            foreach ($xmlArray[Resources::CONTRIBUTOR] as $contributorXmlItem) {
                $contributorInstance = new Person();
                $contributorInstance->parseXml($contributorXmlItem->asXML());
                $contributor[] = $contributorInstance;
            }
        } elseif (is_string($contributorItem)) {
            $contributorInstance = new Person();
            $contributorInstance->setName($contributorItem);
            $contributor[] = $contributorInstance;
        } else {
            $contributorInstance = new Person();
            if (!is_array($contributorItem) && is_string($contributorItem->asXML())) {
                $contributorInstance->parseXml($contributorItem->asXML());
                $contributor[] = $contributorInstance;
            }
        }

        return $contributor;
    }

    /**
     * Processes link node.
     *
     * @param array<string, array|string|SimpleXMLElement> $xmlArray An array of simple xml elements
     * @return array<int, AtomLink>
     */
    protected function processLinkNode(array $xmlArray): array
    {
        $link = [];
        $linkValue = $xmlArray[Resources::LINK];

        if (is_array($xmlArray[Resources::LINK])) {
            foreach ($xmlArray[Resources::LINK] as $linkValueInstance) {
                $linkInstance = new AtomLink();
                if (is_string($linkValueInstance->asXML())) {
                    $linkInstance->parseXml($linkValueInstance->asXML());
                    $link[] = $linkInstance;
                }
            }
        } else {
            $linkInstance = new AtomLink();
            if ( $linkValue instanceof SimpleXMLElement && is_string($linkValue->asXML())) {
                $linkInstance->parseXml($linkValue->asXML());
                $link[] = $linkInstance;
            }
        }

        return $link;
    }

    /**
     * Writes an optional attribute for ATOM.
     *
     * @param XMLWriter $xmlWriter      The XML writer
     * @param string    $attributeName  The name of the attribute
     * @param mixed     $attributeValue The value of the attribute
     */
    protected function writeOptionalAttribute(
        XMLWriter $xmlWriter,
        string    $attributeName,
                  $attributeValue
    ): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');
        Validate::isString($attributeName, 'attributeName');

        if (! is_null($attributeValue)) {
            $xmlWriter->writeAttribute(
                $attributeName,
                $attributeValue
            );
        }
    }

    /**
     * Writes the optional elements namespaces.
     *
     * @param XMLWriter $xmlWriter    The XML writer
     * @param string    $prefix       The prefix
     * @param string    $elementName  The element name
     * @param string    $namespace    The namespace name
     * @param null|string    $elementValue The element value
     */
    protected function writeOptionalElementNS(
        XMLWriter $xmlWriter,
        string    $prefix,
        string    $elementName,
        string    $namespace,
        ?string    $elementValue
    ): void
    {
        Validate::isString($elementName, 'elementName');

        if (! is_null(($elementValue))) {
            $xmlWriter->writeElementNs(
                $prefix,
                $elementName,
                $namespace,
                $elementValue
            );
        }
    }
}
