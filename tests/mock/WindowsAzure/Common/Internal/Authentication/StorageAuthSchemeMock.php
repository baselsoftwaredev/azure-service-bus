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

namespace Tests\mock\WindowsAzure\Common\Internal\Authentication;

use WindowsAzure\Common\Internal\Authentication\StorageAuthScheme;

/**
 * Mock class to wrap StorageAuthScheme class.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class StorageAuthSchemeMock extends StorageAuthScheme
{
    /**
     * Constructor.
     *
     * @param string $accountName storage account name
     * @param string $accountKey  storage account primary or secondary key
     */
    public function __construct(string $accountName, string $accountKey)
    {
        parent::__construct($accountName, $accountKey);
    }

    /**
     * @param array<string, string> $headers
     * @return array<int, string>
     */
    public function computeCanonicalizedHeadersMock(array $headers): array
    {
        return parent::computeCanonicalizedHeaders($headers);
    }

    /**
     * @param string                $url
     * @param array<string, string> $queryParams
     * @return string
     */
    public function computeCanonicalizedResourceMock(string $url, array $queryParams): string
    {
        return parent::computeCanonicalizedResource($url, $queryParams);
    }

    /**
     * @param string                $url
     * @param array<string, string> $queryParams
     * @return string
     */
    public function computeCanonicalizedResourceForTableMock(string $url, array $queryParams): string
    {
        return parent::computeCanonicalizedResourceForTable($url, $queryParams);
    }

    /**
     * @return string
     */
    public function getAccountName(): string
    {
        return $this->accountName;
    }

    /**
     * @return string
     */
    public function getAccountKey(): string
    {
        return $this->accountKey;
    }

    /**
     * Returns authorization header to be included in the request.
     * Specifying the Authorization Header section at http://msdn.microsoft.com/en-us/library/windowsazure/dd179428.aspx
     *
     * @param array<string, string> $headers     request headers
     * @param string                $url         request URL
     * @param array<string, string> $queryParams query variables
     * @param string                $httpMethod  request http method
     * @return string
     */
    public function getAuthorizationHeader(array $headers, string $url, array $queryParams, string $httpMethod): string
    {
        return '';
    }

    /**
     * Computes the authorization signature.
     * Check all authentication schemes at http://msdn.microsoft.com/en-us/library/windowsazure/dd179428.aspx
     *
     * @param array<string, string> $headers     request headers
     * @param string                $url         request URL
     * @param array<string, string> $queryParams query variables
     * @param string                $httpMethod  request http method
     * @return string
     */
    protected function computeSignature(array $headers, string $url, array $queryParams, string $httpMethod): string
    {
        return '';
    }
}
