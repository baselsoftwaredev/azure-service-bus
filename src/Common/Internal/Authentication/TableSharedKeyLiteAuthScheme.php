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

namespace WindowsAzure\Common\Internal\Authentication;

use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\Internal\Utilities;

/**
 * Provides shared key authentication scheme for blob and queue. For more info
 * check: http://msdn.microsoft.com/en-us/library/windowsazure/dd179428.aspx.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class TableSharedKeyLiteAuthScheme extends StorageAuthScheme
{
    /**
     * @var array<int, string>
     */
    protected array $includedHeaders;

    /**
     * Constructor.
     *
     * @param string $accountName storage account name
     * @param string $accountKey  storage account primary or secondary key
     */
    public function __construct(string $accountName, string $accountKey)
    {
        parent::__construct($accountName, $accountKey);

        $this->includedHeaders = [];
        $this->includedHeaders[] = Resources::DATE;
    }

    /**
     * Returns authorization header to be included in the request.
     * See Specifying the Authorization Header section at
     * http://msdn.microsoft.com/en-us/library/windowsazure/dd179428.aspx
     *
     * @param array<string, string> $headers     request headers
     * @param string                $url         request URL
     * @param array<string, string> $queryParams query variables
     * @param string                $httpMethod  request http method
     * @return string
     */
    public function getAuthorizationHeader(array $headers, string $url, array $queryParams, string $httpMethod): string
    {
        $signature = $this->computeSignature($headers, $url, $queryParams, $httpMethod);
        $decodedKey = base64_decode($this->accountKey, true);
        $decodedKey = is_string($decodedKey) ? $decodedKey : '';

        return 'SharedKeyLite ' . $this->accountName . ':' . base64_encode(hash_hmac('sha256', $signature, $decodedKey, true));
    }

    /**
     * Computes the authorization signature for blob and queue shared key.
     * See Blob and Queue Services (Shared Key Authentication) at
     * http://msdn.microsoft.com/en-us/library/windowsazure/dd179428.aspx
     *
     * @param array<string, string> $headers     request headers
     * @param string                $url         request URL
     * @param array<string, string> $queryParams query variables
     * @param string                $httpMethod  request http method
     * @return string
     */
    protected function computeSignature(array $headers, string $url, array $queryParams, string $httpMethod): string
    {
        $canonicalizedResource = parent::computeCanonicalizedResourceForTable($url, $queryParams);

        $stringToSign = [];

        foreach ($this->includedHeaders as $header) {
            $stringToSign[] = Utilities::tryGetValue($headers, $header);
        }

        $stringToSign[] = $canonicalizedResource;
        return implode("\n", $stringToSign);
    }
}
