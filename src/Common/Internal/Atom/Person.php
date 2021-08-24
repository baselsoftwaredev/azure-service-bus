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

use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\Internal\Validate;
use XMLWriter;

/**
 * The person class of ATOM library.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class Person extends AtomBase
{
    /**
     * The name of the person.
     */
    protected string $name;

    /**
     * The Uri of the person.
     */
    protected ?string $uri = null;

    /**
     * The email of the person.
     */
    protected ?string $email = null;

    /**
     * Creates an ATOM person instance with specified name.
     *
     * @param string $name The name of the person
     */
    public function __construct(string $name = Resources::EMPTY_STRING)
    {
        $this->name = $name;
    }

    /**
     * Populates the properties with a specified XML string.
     *
     * @param string $xmlString An XML based string representing
     *                          the Person instance
     */
    public function parseXml(string $xmlString): void
    {
        $personXml = simplexml_load_string($xmlString);
        $personArray = (array) $personXml;

        if (array_key_exists('name', $personArray)) {
            $this->name = (string) $personArray['name'];
        }

        if (array_key_exists('uri', $personArray)) {
            $this->uri = (string) $personArray['uri'];
        }

        if (array_key_exists('email', $personArray)) {
            $this->email = (string) $personArray['email'];
        }
    }

    /**
     * Gets the name of the person.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the person.
     *
     * @param string $name The name of the person
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Gets the URI of the person.
     *
     * @return ?string
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * Sets the URI of the person.
     *
     * @param string $uri The URI of the person
     */
    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * Gets the email of the person.
     *
     * @return ?string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the email of the person.
     *
     * @param string $email The email of the person
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Writes an XML representing the person.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');
        $xmlWriter->startElementNs(
            'atom',
            'person',
            Resources::ATOM_NAMESPACE
        );
        $this->writeInnerXml($xmlWriter);
        $xmlWriter->endElement();
    }

    /**
     * Writes an inner XML representing the person.
     *
     * @param XMLWriter $xmlWriter The XML writer
     */
    public function writeInnerXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');
        $xmlWriter->writeElementNs(
            'atom',
            'name',
            Resources::ATOM_NAMESPACE,
            $this->name
        );

        $this->writeOptionalElementNS(
            $xmlWriter,
            'atom',
            'uri',
            Resources::ATOM_NAMESPACE,
            $this->uri
        );

        $this->writeOptionalElementNS(
            $xmlWriter,
            'atom',
            'email',
            Resources::ATOM_NAMESPACE,
            $this->email
        );
    }
}
