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

use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\Internal\Utilities;
use WindowsAzure\Common\Internal\Validate;

/**
 * Holds basic elements for making HTTP call.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class HttpCallContext
{
    /**
     * The HTTP method used to make this call.
     */
    private ?string $_method = null;

    /**
     * HTTP request headers.
     *
     * @var array<string, string>
     */
    private array $_headers = [];

    /**
     * The URI query parameters.
     *
     * @var array<string, string>
     */
    private $_queryParams = [];

    /**
     * The HTTP POST parameters.
     *
     * @var array<string, string>
     */
    private $_postParameters = [];

    private ?string $_uri = null;

    /**
     * The URI path.
     */
    private ?string $_path = null;

    /**
     * The expected status codes.
     *
     * @var array<int, int>
     */
    private array $_statusCodes = [];

    /**
     * The HTTP request body.
     *
     * @var ?string
     */
    private ?string $_body = null;

    /**
     * Gets method.
     *
     * @return ?string
     */
    public function getMethod(): ?string
    {
        return $this->_method;
    }

    /**
     * Sets method.
     *
     * @param string $method The method value
     */
    public function setMethod(string $method): void
    {
        $this->_method = $method;
    }

    /**
     * Gets headers.
     *
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->_headers;
    }

    /**
     * Sets headers.
     * Ignores the header if its value is empty.
     *
     * @param array<string, string> $headers The headers value
     */
    public function setHeaders(array $headers): void
    {
        $this->_headers = [];
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }
    }

    /**
     * Adds or sets header pair.
     *
     * @param string $name  The HTTP header name
     * @param string $value The HTTP header value
     */
    public function addHeader(string $name, string $value): void
    {
        if ($value === '') {
            return;
        }
        $this->_headers[$name] = $value;
    }

    /**
     * Gets queryParams.
     *
     * @return array<string, string>
     */
    public function getQueryParameters(): array
    {
        return $this->_queryParams;
    }

    /**
     * Sets queryParams.
     * Ignores the query variable if its value is empty.
     *
     * @param array<string, string> $queryParams The queryParams value
     */
    public function setQueryParameters(array $queryParams): void
    {
        $this->_queryParams = [];
        foreach ($queryParams as $key => $value) {
            $this->addQueryParameter($key, $value);
        }
    }

    /**
     * Adds or sets query parameter pair.
     *
     * @param string $key   The URI query parameter key
     * @param string $value The URI query parameter value
     */
    public function addQueryParameter(string $key, string $value): void
    {
        $this->_queryParams[$key] = $value;
    }

    /**
     * Gets uri.
     *
     * @return ?string
     */
    public function getUri(): ?string
    {
        return $this->_uri;
    }

    /**
     * Sets uri.
     *
     * @param string $uri The uri value
     */
    public function setUri(string $uri): void
    {
        $this->_uri = $uri;
    }

    /**
     * Gets path.
     *
     * @return ?string
     */
    public function getPath(): ?string
    {
        return $this->_path;
    }

    /**
     * Sets path.
     *
     * @param string $path The path value
     */
    public function setPath(string $path): void
    {
        $this->_path = $path;
    }

    /**
     * Gets statusCodes.
     *
     * @return array<int, int>
     */
    public function getStatusCodes(): array
    {
        return $this->_statusCodes;
    }

    /**
     * Sets statusCodes.
     *
     * @param array<int, int> $statusCodes The statusCodes value
     */
    public function setStatusCodes(array $statusCodes): void
    {
        $this->_statusCodes = [];
        foreach ($statusCodes as $value) {
            $this->addStatusCode($value);
        }
    }

    /**
     * Adds status code to the expected status codes.
     *
     * @param int $statusCode The expected status code
     */
    public function addStatusCode(int $statusCode): void
    {
        $this->_statusCodes[] = $statusCode;
    }

    /**
     * Gets body.
     *
     * @return string
     */
    public function getBody(): ?string
    {
        return $this->_body;
    }

    /**
     * Sets body.
     *
     * @param string $body The body value
     */
    public function setBody(string $body): void
    {
        $this->_body = $body;
    }

    /**
     * Adds or sets header pair.
     * Ignores header if it's value satisfies empty().
     *
     * @param string $name  The HTTP header name
     * @param string $value The HTTP header value
     */
    public function addOptionalHeader(string $name, string $value): void
    {
        $this->_headers[$name] = $value;
    }

    /**
     * Removes header from the HTTP request headers.
     *
     * @param string $name The HTTP header name
     */
    public function removeHeader(string $name): void
    {
        Validate::notNullOrEmpty($name, 'name');

        unset($this->_headers[$name]);
    }

    /**
     * Gets HTTP POST parameters.
     *
     * @return array<string, string>
     */
    public function getPostParameters(): array
    {
        return $this->_postParameters;
    }

    /**
     * Sets HTTP POST parameters.
     *
     * @param array<string, string> $postParameters The HTTP POST parameters
     */
    public function setPostParameters(array $postParameters): void
    {
        $this->_postParameters = $postParameters;
    }

    /**
     * Gets header value.
     *
     * @param string $name The header name
     * @return mixed
     */
    public function getHeader(string $name)
    {
        return Utilities::tryGetValue($this->_headers, $name);
    }

    /**
     * Converts the context object to string.
     *
     * @return string
     */
    public function __toString()
    {
        $headers = Resources::EMPTY_STRING;
        $uri = $this->_uri !== null ? $this->_uri : 'no-uri';
        $uri = new Url($uri);
        $uri = $uri->getUrl();

        foreach ($this->_headers as $key => $value) {
            $headers .= "$key: $value\n";
        }

        $str = "$this->_method $uri$this->_path HTTP/1.1\n$headers\n";
        $str .= "$this->_body";

        return $str;
    }
}
