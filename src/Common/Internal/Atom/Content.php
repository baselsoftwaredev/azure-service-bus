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
 * The content class of ATOM standard.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/WindowsAzure/azure-sdk-for-php
 * @version   Release: 0.5.0_2016-11
 * @category  Microsoft
 */
class Content extends AtomBase
{
    /**
     * The text of the content.
     */
    protected ?string $text;

    /**
     * The type of the content.
     */
    protected ?string $type = null;

    /**
     * Source XML object.
     */
    protected SimpleXMLElement $xml;

    /**
     * Creates a Content instance with specified text.
     *
     * @param string|null $text The text of the content
     */
    public function __construct(string $text = null)
    {
        $this->text = $text;
    }

    /**
     * Creates an ATOM CONTENT instance with specified xml string.
     *
     * @param string $xmlString an XML based string of ATOM CONTENT
     */
    public function parseXml(string $xmlString): void
    {
        Validate::notNull($xmlString, 'xmlString');
        Validate::isString($xmlString, 'xmlString');
        $xml = simplexml_load_string($xmlString);

        if ($xml !== false) {
            $this->fromXml($xml);
        }
    }

    /**
     * Creates an ATOM CONTENT instance with specified simpleXML object.
     *
     * @param SimpleXMLElement $contentXml xml element of ATOM CONTENT
     */
    public function fromXml(SimpleXMLElement $contentXml): void
    {
        Validate::notNull($contentXml, 'contentXml');

        $attributes = $contentXml->attributes();

        if (! is_null($attributes) && (string) $attributes['type'] !== '') {
            $this->type = (string) $attributes['type'];
        }

        $text = '';
        foreach ($contentXml->children() as $child) {
            $text .= $child->asXML();
        }

        $this->text = $text;

        $this->xml = $contentXml;
    }

    /**
     * Gets the text of the content.
     *
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Sets the text of the content.
     *
     * @param string $text The text of the content
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Gets the xml object of the content.
     *
     * @return SimpleXMLElement
     */
    public function getXml(): SimpleXMLElement
    {
        return $this->xml;
    }

    /**
     * Gets the type of the content.
     *
     * @return ?string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the type of the content.
     *
     * @param string $type The type of the content
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Writes an XML representing the content.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');
        $xmlWriter->startElementNs(
            'atom',
            'content',
            Resources::ATOM_NAMESPACE
        );

        $this->writeOptionalAttribute(
            $xmlWriter,
            'type',
            $this->type
        );

        $this->writeInnerXml($xmlWriter);
        $xmlWriter->endElement();
    }

    /**
     * Writes an inner XML representing the content.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeInnerXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');
        if (! is_null($this->text)) {
            $xmlWriter->writeRaw($this->text);
        }
    }
}
