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

namespace WindowsAzure\Common\Internal\Http;

use Exception;
use InvalidArgumentException;
use Net_URL2;
use WindowsAzure\Common\Internal\Resources;

/**
 * Default IUrl implementation.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class Url implements IUrl
{
    private Net_URL2 $_url;

    /**
     * Constructor.
     *
     * @param string $url the url to set
     * @throws Exception
     */
    public function __construct(string $url)
    {
        $this->validate($url);
        $this->_url = new Net_URL2($url);
        $this->_setPathIfEmpty($url);
    }

    /**
     * Check string is a valid URL.
     *
     * @param string $url
     */
    private function validate(string $url): void
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException(Resources::INVALID_URL_MSG);
        }
    }

    /**
     * Sets the url path to '/' if it's empty.
     *
     * @param string $url the url string
     */
    private function _setPathIfEmpty(string $url): void
    {
        $path = parse_url($url, PHP_URL_PATH);

        if (! is_string($path)) {
            $this->setUrlPath('/');
        }
    }

    /**
     * Sets url path.
     *
     * @param string $urlPath url path to set
     */
    public function setUrlPath(string $urlPath): void
    {
        $this->_url->setPath($urlPath);
    }

    /**
     * Makes deep copy from the current object.
     */
    public function __clone()
    {
        $this->_url = clone $this->_url;
    }

    /**
     * Returns the query portion of the url.
     *
     * @return bool|string
     */
    public function getQuery()
    {
        return $this->_url->getQuery();
    }

    /**
     * Returns the query portion of the url in array form.
     *
     * @return array<string, string>
     */
    public function getQueryVariables(): array
    {
        return $this->_url->getQueryVariables();
    }

    /**
     * Gets actual URL string.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->_url->getURL();
    }

    /**
     * Appends url path.
     *
     * @param string $urlPath url path to append
     */
    public function appendUrlPath(string $urlPath): void
    {
        $newUrlPath = parse_url($this->_url, PHP_URL_PATH) . $urlPath;
        $this->_url->setPath($newUrlPath);
    }

    /**
     * Gets actual URL string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->_url->getURL();
    }

    /**
     * Sets the query string to the specified variables in $array.
     *
     * @param array<string, string> $array key/value representation of query variables
     */
    public function setQueryVariables(array $array): void
    {
        foreach ($array as $key => $value) {
            $this->setQueryVariable($key, $value);
        }
    }

    /**
     * Sets an existing query parameter to value or creates a new one if the $key
     * doesn't exist.
     *
     * @param string $key   query parameter name
     * @param string $value query value
     */
    public function setQueryVariable(string $key, string $value): void
    {
        $this->_url->setQueryVariable($key, $value);
    }
}
