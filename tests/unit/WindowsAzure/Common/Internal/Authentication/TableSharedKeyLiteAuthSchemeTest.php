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

namespace Tests\unit\WindowsAzure\Common\Internal\Authentication;

use PHPUnit\Framework\TestCase;
use Tests\framework\TestResources;
use Tests\mock\WindowsAzure\Common\Internal\Authentication\TableSharedKeyLiteAuthSchemeMock;
use WindowsAzure\Common\Internal\Resources;

/**
 * Unit tests for TableSharedKeyLiteAuthScheme class.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class TableSharedKeyLiteAuthSchemeTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Authentication\TableSharedKeyLiteAuthScheme::__construct
     */
    public function test__construct(): void
    {
        $expected = [];
        $expected[] = Resources::DATE;

        $mock = new TableSharedKeyLiteAuthSchemeMock(TestResources::ACCOUNT_NAME, TestResources::KEY4);

        self::assertEquals($expected, $mock->getIncludedHeaders());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Authentication\TableSharedKeyLiteAuthScheme::computeSignature
     */
    public function testComputeSignatureSimple(): void
    {
        $httpMethod = 'GET';
        $queryParams = [Resources::QP_COMP => 'list'];
        $url = TestResources::URI1;
        $date = TestResources::DATE1;
        $apiVersion = Resources::STORAGE_API_LATEST_VERSION;
        $accountName = TestResources::ACCOUNT_NAME;
        $headers = [Resources::X_MS_DATE => $date, Resources::X_MS_VERSION => $apiVersion];
        $expected = "\n/$accountName" . parse_url($url, PHP_URL_PATH) . '?comp=list';
        $mock = new TableSharedKeyLiteAuthSchemeMock($accountName, TestResources::KEY4);

        $actual = $mock->computeSignatureMock($headers, $url, $queryParams, $httpMethod);

        self::assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Authentication\TableSharedKeyLiteAuthScheme::getAuthorizationHeader
     */
    public function testGetAuthorizationHeaderSimple(): void
    {
        $accountName = TestResources::ACCOUNT_NAME;
        $apiVersion = Resources::STORAGE_API_LATEST_VERSION;
        $accountKey = TestResources::KEY4;
        $url = TestResources::URI2;
        $date1 = TestResources::DATE2;
        $headers = [Resources::X_MS_VERSION => $apiVersion, Resources::X_MS_DATE => $date1];
        $queryParams = [Resources::QP_COMP => 'list'];
        $httpMethod = 'GET';
        $expected = 'SharedKeyLite ' . $accountName . ':KB+TK3FPHLADYwd0/b3PcZgK/fYXUSlwsoOIf80l2co=';

        $mock = new TableSharedKeyLiteAuthSchemeMock($accountName, $accountKey);

        $actual = $mock->getAuthorizationHeader($headers, $url, $queryParams, $httpMethod);

        self::assertEquals($expected, $actual);
    }
}
