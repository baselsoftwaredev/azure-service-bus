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
use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\ServiceBus\Internal\IWrap;
use WindowsAzure\ServiceBus\Internal\WrapTokenManager;

/**
 * Adds WRAP authentication header to the http request object.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class WrapFilter implements IServiceFilter
{
    /**
     * @var WrapTokenManager
     */
    private WrapTokenManager $_wrapTokenManager;

    /**
     * Creates a WrapFilter with specified WRAP parameters.
     *
     * @param string $wrapUri       The URI of the WRAP service
     * @param string $wrapUsername  The username of the WRAP account
     * @param string $wrapPassword  The password of the WRAP account
     * @param IWrap  $wrapRestProxy The WRAP service REST proxy
     */
    public function __construct(string $wrapUri, string $wrapUsername, string $wrapPassword, IWrap $wrapRestProxy
    )
    {
        $this->_wrapTokenManager = new WrapTokenManager(
            $wrapUri,
            $wrapUsername,
            $wrapPassword,
            $wrapRestProxy
        );
    }

    /**
     * Adds WRAP authentication header to the request headers.
     *
     * @param IHttpClient $request HTTP channel object
     * @return IHttpClient
     */
    public function handleRequest(IHttpClient $request): IHttpClient
    {
        $wrapAccessToken = $this->_wrapTokenManager->getAccessToken(
            $request->getUrl()
        );

        $authorization = sprintf(
            Resources::WRAP_AUTHORIZATION,
            $wrapAccessToken
        );

        $request->setHeader(Resources::AUTHENTICATION, $authorization);

        return $request;
    }

    /**
     * Returns the original response.
     *
     * @param IHttpClient       $request  An HTTP channel object
     * @param ResponseInterface $response An HTTP response object
     * @return ResponseInterface
     */
    public function handleResponse(IHttpClient $request, ResponseInterface $response): ResponseInterface
    {
        return $response;
    }
}
