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

use Exception;
use SimpleXMLElement;
use WindowsAzure\Common\Internal\Resources;
use XMLWriter;

/**
 * The generator class of ATOM library.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class Generator extends AtomBase
{
    /**
     * The of the generator.
     */
    protected ?string $text = null;

    /**
     * The Uri of the generator.
     */
    protected ?string $uri = null;

    /**
     * The version of the generator.
     */
    protected ?string $version = null;

    /**
     * Creates an ATOM generator instance with specified name.
     *
     * @param ?string $text The text content of the generator
     */
    public function __construct(string $text = null)
    {
        if (! is_null($text)) {
            $this->text = $text;
        }
    }

    /**
     * Creates a generator instance with specified XML string.
     *
     * @param string $xmlString A string representing a generator
     *                          instance
     * @throws Exception
     */
    public function parseXml(string $xmlString): void
    {
        $generatorXml = new SimpleXMLElement($xmlString);
        $attributes = (array) $generatorXml->attributes();
        if (! is_null($attributes['uri'])) {
            $this->uri = (string) $attributes['uri'];
        }

        if (! is_null($attributes['version'])) {
            $this->version = (string) $attributes['version'];
        }

        $this->text = (string) $generatorXml;
    }

    /**
     * Gets the text of the generator.
     *
     * @return ?string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Sets the text of the generator.
     *
     * @param string $text The text of the generator
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Gets the URI of the generator.
     *
     * @return ?string
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * Sets the URI of the generator.
     *
     * @param string $uri The URI of the generator
     */
    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * Gets the version of the generator.
     *
     * @return ?string
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * Sets the version of the generator.
     *
     * @param string $version The version of the generator
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * Writes an XML representing the generator.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeXml(XMLWriter $xmlWriter): void
    {
        $xmlWriter->startElementNs(
            'atom',
            Resources::CATEGORY,
            Resources::ATOM_NAMESPACE
        );

        $this->writeOptionalAttribute(
            $xmlWriter,
            'uri',
            $this->uri
        );

        $this->writeOptionalAttribute(
            $xmlWriter,
            'version',
            $this->version
        );

        $xmlWriter->writeRaw((string) $this->text);
        $xmlWriter->endElement();
    }
}
