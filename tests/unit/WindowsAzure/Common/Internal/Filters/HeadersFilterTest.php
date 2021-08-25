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

namespace Tests\unit\WindowsAzure\Common\Internal\Filters;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use WindowsAzure\Common\Internal\Filters\HeadersFilter;
use WindowsAzure\Common\Internal\Http\HttpClient;
use WindowsAzure\Common\Internal\Resources;

/**
 * Unit tests for class HeadersFilter.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class HeadersFilterTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Filters\HeadersFilter::handleRequest
     * @covers \WindowsAzure\Common\Internal\Filters\HeadersFilter::__construct
     */
    public function testHandleRequestEmptyHeaders(): void
    {
        $channel = new HttpClient();
        $filter = new HeadersFilter([]);

        $request = $filter->handleRequest($channel);

        $headers = $request->getHeaders();
        // Assert. there are two header returned back
        // 'User-Agent'.
        self::assertCount(1, $headers);
        self::assertEquals(Resources::SDK_USER_AGENT, $headers['user-agent']);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Filters\HeadersFilter::handleRequest
     * @covers \WindowsAzure\Common\Internal\Filters\HeadersFilter::__construct
     */
    public function testHandleRequestOneHeader(): void
    {
        $channel = new HttpClient();
        $header1 = 'header1';
        $value1 = 'value1';
        $expected = [$header1 => $value1];
        $filter = new HeadersFilter($expected);

        $request = $filter->handleRequest($channel);

        $headers = $request->getHeaders();
        self::assertEquals($value1, $headers[$header1]);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Filters\HeadersFilter::handleRequest
     * @covers \WindowsAzure\Common\Internal\Filters\HeadersFilter::__construct
     */
    public function testHandleRequestMultipleHeaders(): void
    {
        $channel = new HttpClient();
        $header1 = 'header1';
        $value1 = 'value1';
        $header2 = 'header2';
        $value2 = 'value2';
        $expected = [$header1 => $value1, $header2 => $value2];
        $filter = new HeadersFilter($expected);

        $request = $filter->handleRequest($channel);

        $headers = $request->getHeaders();
        self::assertEquals($value1, $headers[$header1]);
        self::assertEquals($value2, $headers[$header2]);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Filters\HeadersFilter::handleResponse
     */
    public function testHandleResponse(): void
    {
        $channel = new HttpClient();
        $response = new Response();
        $filter = new HeadersFilter([]);

        $response = $filter->handleResponse($channel, $response);

        $this->assertNotNull($response);
    }
}
