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
use WindowsAzure\Common\Internal\Validate;
use XMLWriter;

/**
 * This link defines a reference from an entry or feed to a Web resource.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class AtomLink extends AtomBase
{
    /**
     * The undefined content.
     */
    protected ?string $undefinedContent = null;

    /**
     * The HREF of the link.
     */
    protected ?string $href = null;

    /**
     * The rel attribute of the link.
     */
    protected ?string $rel = null;

    /**
     * The media type of the link.
     */
    protected ?string $type = null;

    /**
     * The language of HREF.
     */
    protected ?string $hreflang = null;

    /**
     * The title of the link.
     */
    protected ?string $title = null;

    /**
     * The length of the link.
     */
    protected ?string $length = null;

    /**
     * Parse an ATOM Link xml.
     *
     * @param string $xmlString an XML based string of ATOM Link
     * @throws Exception
     */
    public function parseXml(string $xmlString): void
    {
        $atomLinkXml = simplexml_load_string($xmlString);
        $attributes = $atomLinkXml !== false ? $atomLinkXml->attributes() : null;

        if (! is_null($attributes)) {
            $this->validateAttributes($attributes);
        }

        if ($atomLinkXml === false) {
            $this->undefinedContent = null;
        } else {
            $this->undefinedContent = (string) $atomLinkXml;
        }
    }


    /**
     * Validate attributes
     *
     * @param SimpleXMLElement $attributes
     */
    protected function validateAttributes(SimpleXMLElement $attributes): void
    {
        if ((string) $attributes['href'] !== '') {
            $this->href = $attributes['href'];
        }

        if ((string) $attributes['rel'] !== '') {
            $this->rel = $attributes['rel'];
        }

        if ((string) $attributes['type'] !== '') {
            $this->type = $attributes['type'];
        }

        if ((string) $attributes['hreflang'] !== '') {
            $this->hreflang = $attributes['hreflang'];
        }

        if ((string) $attributes['title'] !== '') {
            $this->title = $attributes['title'];
        }

        if ((string) $attributes['length'] !== '') {
            $this->length = (string) $attributes['length'];
        }
    }

    /**
     * Gets the href of the link.
     *
     * @return ?string
     */
    public function getHref(): ?string
    {
        return $this->href;
    }

    /**
     * Sets the href of the link.
     *
     * @param string $href The href of the link
     */
    public function setHref(string $href): void
    {
        $this->href = $href;
    }

    /**
     * Gets the rel of the atomLink.
     *
     * @return string
     */
    public function getRel(): ?string
    {
        return $this->rel;
    }

    /**
     * Sets the rel of the link.
     *
     * @param string $rel The rel of the atomLink
     */
    public function setRel(string $rel): void
    {
        $this->rel = $rel;
    }

    /**
     * Gets the type of the link.
     *
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets the type of the link.
     *
     * @param string $type The type of the link
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Gets the language of the href.
     *
     * @return ?string
     */
    public function getHreflang(): ?string
    {
        return $this->hreflang;
    }

    /**
     * Sets the language of the href.
     *
     * @param string $hreflang The language of the href
     */
    public function setHreflang(string $hreflang): void
    {
        $this->hreflang = $hreflang;
    }

    /**
     * Gets the title of the link.
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the link.
     *
     * @param string $title The title of the link
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Gets the length of the link.
     *
     * @return ?string
     */
    public function getLength(): ?string
    {
        return $this->length;
    }

    /**
     * Sets the length of the link.
     *
     * @param string $length The length of the link
     */
    public function setLength(string $length): void
    {
        $this->length = $length;
    }

    /**
     * Gets the undefined content.
     *
     * @return null|string
     */
    public function getUndefinedContent(): ?string
    {
        return $this->undefinedContent;
    }

    /**
     * Sets the undefined content.
     *
     * @param string $undefinedContent The undefined content
     */
    public function setUndefinedContent(string $undefinedContent): void
    {
        $this->undefinedContent = $undefinedContent;
    }

    /**
     * Writes an XML representing the ATOM link item.
     *
     * @param XMLWriter $xmlWriter The xml writer
     */
    public function writeXml(XMLWriter $xmlWriter): void
    {
        $xmlWriter->startElementNs(
            'atom',
            Resources::LINK,
            Resources::ATOM_NAMESPACE
        );
        $this->writeInnerXml($xmlWriter);
        $xmlWriter->endElement();
    }

    /**
     * Writes the inner XML representing the ATOM link item.
     *
     * @param XMLWriter $xmlWriter The xml writer
     */
    public function writeInnerXml(XMLWriter $xmlWriter): void
    {
        Validate::notNull($xmlWriter, 'xmlWriter');

        $this->writeOptionalAttribute($xmlWriter, 'href', $this->href);
        $this->writeOptionalAttribute($xmlWriter, 'rel', $this->rel);
        $this->writeOptionalAttribute($xmlWriter, 'type', $this->type);
        $this->writeOptionalAttribute($xmlWriter, 'hreflang', $this->hreflang);
        $this->writeOptionalAttribute($xmlWriter, 'title', $this->title);
        $this->writeOptionalAttribute($xmlWriter, 'length', $this->length);

        if (! is_null($this->undefinedContent)) {
            $xmlWriter->writeRaw($this->undefinedContent);
        }
    }
}
