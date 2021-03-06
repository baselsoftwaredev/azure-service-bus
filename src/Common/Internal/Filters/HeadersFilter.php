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

namespace WindowsAzure\Common\Internal\Filters;

use Psr\Http\Message\ResponseInterface;
use WindowsAzure\Common\Internal\Http\IHttpClient;
use WindowsAzure\Common\Internal\IServiceFilter;

/**
 * Adds all passed headers to the HTTP request headers.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class HeadersFilter implements IServiceFilter
{
    /**
     * @var array<string, string>
     */
    private array $_headers;

    /**
     * Constructor.
     *
     * @param array<string, string> $headers static headers to be added
     */
    public function __construct(array $headers)
    {
        $this->_headers = $headers;
    }

    /**
     * Adds static header(s) to the HTTP request headers.
     *
     * @param IHttpClient $request HTTP channel object
     * @return IHttpClient
     */
    public function handleRequest(IHttpClient $request): IHttpClient
    {
        foreach ($this->_headers as $key => $value) {
            $headers = $request->getHeaders();
            if (! array_key_exists($key, $headers)) {
                $request->setHeader($key, $value);
            }
        }

        return $request;
    }

    /**
     * Does nothing with the response.
     *
     * @param IHttpClient       $request  HTTP channel object
     * @param ResponseInterface $response HTTP response object
     * @return ResponseInterface
     */
    public function handleResponse(IHttpClient $request, ResponseInterface $response): ResponseInterface
    {
        // Do nothing with the response.
        return $response;
    }
}
