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

use SimpleXMLElement;
use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\Internal\Validate;
use XMLWriter;

/**
 * The category class of the ATOM standard.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class Category extends AtomBase
{
    /**
     * The term of the category.
     */
    protected ?string $term = null;

    /**
     * The scheme of the category.
     */
    protected ?string $scheme = null;

    /**
     * The label of the category.
     */
    protected ?string $label = null;

    /**
     * The undefined content of the category.
     */
    protected ?string $undefinedContent = null;

    /**
     * Creates a Category instance with specified text.
     *
     * @param string $undefinedContent The undefined content of the category
     */
    public function __construct(string $undefinedContent = Resources::EMPTY_STRING)
    {
        $this->undefinedContent = $undefinedContent;
    }

    /**
     * Creates an ATOM Category instance with specified xml string.
     *
     * @param string $xmlString an XML based string of ATOM CONTENT
     */
    public function parseXml(string $xmlString): void
    {
        Validate::notNull($xmlString, 'xmlString');
        Validate::isString($xmlString, 'xmlString');
        $categoryXml = simplexml_load_string($xmlString);
        $attributes = $categoryXml !== false ? $categoryXml->attributes() : null;

        if (! is_null($attributes)) {
            $this->validateAttributes($attributes);
        }

        $this->undefinedContent = (string) $categoryXml;
    }

    /**
     * Validate attributes
     *
     * @param SimpleXMLElement $attributes
     */
    protected function validateAttributes(SimpleXMLElement $attributes): void
    {
        if ((string) $attributes['term'] !== '') {
            $this->term = (string) $attributes['term'];
        }

        if ((string) $attributes['scheme'] !== '') {
            $this->scheme = (string) $attributes['scheme'];
        }

        if ((string) $attributes['label'] !== '') {
            $this->label = (string) $attributes['label'];
        }
    }

    /**
     * Gets the term of the category.
     *
     * @return ?string
     */
    public function getTerm(): ?string
    {
        return $this->term;
    }

    /**
     * Sets the term of the category.
     *
     * @param string $term The term of the category
     */
    public function setTerm(string $term): void
    {
        $this->term = $term;
    }

    /**
     * Gets the scheme of the category.
     *
     * @return ?string
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * Sets the scheme of the category.
     *
     * @param string $scheme The scheme of the category
     */
    public function setScheme(string $scheme): void
    {
        $this->scheme = $scheme;
    }

    /**
     * Gets the label of the category.
     *
     * @return ?string The label
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Sets the label of the category.
     *
     * @param string $label The label of the category
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * Gets the undefined content of the category.
     *
     * @return ?string
     */
    public function getUndefinedContent(): ?string
    {
        return $this->undefinedContent;
    }

    /**
     * Sets the undefined content of the category.
     *
     * @param string $undefinedContent The undefined content of the category
     */
    public function setUndefinedContent(string $undefinedContent): void
    {
        $this->undefinedContent = $undefinedContent;
    }

    /**
     * Writes an XML representing the category.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');
        $xmlWriter->startElementNs(
            'atom',
            'category',
            Resources::ATOM_NAMESPACE
        );
        $this->writeInnerXml($xmlWriter);
        $xmlWriter->endElement();
    }

    /**
     * Writes an XML representing the category.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeInnerXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');
        $this->writeOptionalAttribute(
            $xmlWriter,
            'term',
            $this->term
        );

        $this->writeOptionalAttribute(
            $xmlWriter,
            'scheme',
            $this->scheme
        );

        $this->writeOptionalAttribute(
            $xmlWriter,
            'label',
            $this->label
        );

        if (!is_null($this->undefinedContent)) {
            $xmlWriter->writeRaw($this->undefinedContent);
        }
    }
}
