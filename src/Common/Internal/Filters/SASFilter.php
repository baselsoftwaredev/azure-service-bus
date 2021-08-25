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
use WindowsAzure\Common\Internal\Utilities;

/**
 * The base class of ATOM library.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class SASFilter implements IServiceFilter
{
    private string $sharedAccessKeyName;

    private string $sharedAccessKey;

    /**
     * @param string $sharedAccessKeyName
     * @param string $sharedAccessKey
     */
    public function __construct(string $sharedAccessKeyName, string $sharedAccessKey)
    {
        $this->sharedAccessKeyName = $sharedAccessKeyName;
        $this->sharedAccessKey = $sharedAccessKey;
    }

    /**
     * Adds WRAP authentication header to the request headers.
     *
     * @param IHttpClient $request HTTP channel object
     * @return IHttpClient
     */
    public function handleRequest(IHttpClient $request): IHttpClient
    {
        $token = $this->getAuthorization(
            $request->getUrl(),
            $this->sharedAccessKeyName,
            $this->sharedAccessKey
        );

        $request->setHeader(Resources::AUTHENTICATION, $token);

        return $request;
    }

    /**
     * @param string $url
     * @param string $sharedAccessKeyName
     * @param string $sharedAccessKey
     * @return string
     */
    private function getAuthorization(string $url, string $sharedAccessKeyName, string $sharedAccessKey): string
    {
        $expiry = time() + 3600;
        $encodedUrl = Utilities::lowerUrlencode($url);
        $scope = $encodedUrl . "\n" . $expiry;
        $signature = base64_encode(hash_hmac('sha256', $scope, $sharedAccessKey, true));
        return sprintf(Resources::SAS_AUTHORIZATION,
            Utilities::lowerUrlencode($signature),
            $expiry,
            $sharedAccessKeyName,
            $encodedUrl
        );
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
