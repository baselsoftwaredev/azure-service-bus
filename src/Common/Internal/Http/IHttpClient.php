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

use Psr\Http\Message\ResponseInterface;
use WindowsAzure\Common\Internal\IServiceFilter;
use WindowsAzure\Common\ServiceException;

/**
 * Defines required methods for an HTTP client proxy.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
interface IHttpClient
{
    /**
     * Throws ServiceException if the received status code is not expected.
     *
     * @param int             $actual   The received status code
     * @param string          $reason   The reason phrase
     * @param string          $message  The detailed message (if any)
     * @param array<int, int> $expected The expected status codes
     * @static
     * @throws ServiceException
     */
    public static function throwIfError(int $actual, string $reason, string $message, array $expected): void;

    /**
     * Sets the request url.
     *
     * @param IUrl $url request url
     */
    public function setUrl(IUrl $url): void;

    /**
     * Gets request url.
     *
     * @return ?IUrl
     */
    public function getUrl(): ?IUrl;

    /**
     * Sets request's HTTP method.
     *
     * @param string $method request's HTTP method
     */
    public function setMethod(string $method): void;

    /**
     * Gets request's HTTP method.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Gets request's headers.
     *
     * @return array<string, string>
     */
    public function getHeaders(): array;

    /**
     * Sets an existing request header to value or creates a new one if the $header
     * doesn't exist.
     *
     * @param string $header  header name
     * @param string $value   header value
     * @param bool   $replace whether to replace previous header with the same name
     *                        or append to its value (comma separated)
     */
    public function setHeader(string $header, string $value, bool $replace = false): void;

    /**
     * Sets request headers using array.
     *
     * @param array<string, string> $headers headers key-value array
     */
    public function setHeaders(array $headers): void;

    /**
     * Sets HTTP POST parameters.
     *
     * @param array<string, string> $postParameters The HTTP POST parameters
     */
    public function setPostParameters(array $postParameters): void;

    /**
     * Processes the request through HTTP pipeline with passed $filters,
     * sends HTTP requests to the wire and process the response in the HTTP pipeline.
     *
     * @param array<int, IServiceFilter> $filters HTTP filters which will be applied to the request before
     *                                            send and then applied to the response
     * @param IUrl|null                  $url     Request url
     * @return ResponseInterface The response
     */
    public function sendAndGetHttpResponse(array $filters, IUrl $url = null): ResponseInterface;

    /**
     * Processes the request through HTTP pipeline with passed $filters,
     * sends HTTP requests to the wire and process the response in the HTTP pipeline.
     *
     * @param array<int, IServiceFilter> $filters HTTP filters which will be applied to the request before
     *                                            send and then applied to the response
     * @param IUrl|null                  $url     Request url
     * @return string The response body
     */
    public function send(array $filters, IUrl $url = null): string;

    /**
     * Sets successful status code.
     *
     * @param array<int, int> $statusCodes successful status code
     */
    public function setExpectedStatusCode(array $statusCodes): void;

    /**
     * Gets successful status code.
     *
     * @return array<int, int>
     */
    public function getSuccessfulStatusCode(): array;

    /**
     * Sets a configuration element for the request.
     *
     * @param string $name  configuration parameter name
     * @param mixed  $value configuration parameter value
     */
    public function setConfig(string $name, $value = null): void;

    /**
     * Gets value for configuration parameter.
     *
     * @param string $name configuration parameter name
     * @return bool|string
     */
    public function getConfig(string $name);

    /**
     * Sets the request body.
     *
     * @param string $body body to use
     */
    public function setBody(string $body): void;

    /**
     * Gets the request body.
     *
     * @return string
     */
    public function getBody(): string;

    /**
     * Makes deep copy from the current object.
     */
    public function __clone();
}
