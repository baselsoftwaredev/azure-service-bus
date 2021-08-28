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

/**
 * Defines what are main url functionalities that should be supported.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
interface IUrl
{
    /**
     * Returns the query portion of the url.
     *
     * @return bool|string
     */
    public function getQuery();

    /**
     * Returns the query portion of the url in array form.
     *
     * @return array<string, string>
     */
    public function getQueryVariables(): array;

    /**
     * Sets an existing query parameter to value or creates a new one if the $key
     * doesn't exist.
     *
     * @param string $key   query parameter name
     * @param string $value query value
     */
    public function setQueryVariable(string $key, string $value): void;

    /**
     * Gets actual URL string.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Sets url path.
     *
     * @param string $urlPath url path to set
     */
    public function setUrlPath(string $urlPath): void;

    /**
     * Appends url path.
     *
     * @param string $urlPath url path to append
     */
    public function appendUrlPath(string $urlPath): void;

    /**
     * Gets actual URL string.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Makes deep copy from the current object.
     */
    public function __clone();

    /**
     * Sets the query string to the specified variables in $array.
     *
     * @param array<string, string> $array key/value representation of query variables
     */
    public function setQueryVariables(array $array): void;
}
