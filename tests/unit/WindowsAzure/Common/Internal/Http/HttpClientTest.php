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

namespace Tests\unit\WindowsAzure\Common\Internal\Http;

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Tests\framework\TestResources;
use Tests\mock\WindowsAzure\Common\Internal\Filters\SimpleFilterMock;
use WindowsAzure\Common\Internal\Http\HttpClient;
use WindowsAzure\Common\Internal\Http\IUrl;
use WindowsAzure\Common\Internal\Http\Url;
use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\ServiceException;

/**
 * Unit tests for class HttpClient.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class HttpClientTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::setUrl
     */
    public function testSetUrl(): void
    {
        $channel = new HttpClient();
        $url = new Url(TestResources::VALID_URL);

        $channel->setUrl($url);

        self::assertInstanceOf(IUrl::class, $channel->getUrl());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::getUrl
     */
    public function testGetUrl(): void
    {
        $channel = new HttpClient();
        $url = new Url(TestResources::VALID_URL);
        $channel->setUrl($url);

        $channelUrl = $channel->getUrl();

        self::assertInstanceOf(IUrl::class, $channelUrl);
        self::assertEquals(TestResources::VALID_URL . '/', $channelUrl);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::setMethod
     */
    public function testSetMethod(): void
    {
        $channel = new HttpClient();
        $httpMethod = 'GET';

        $channel->setMethod($httpMethod);

        self::assertEquals($httpMethod, $channel->getMethod());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::getMethod
     */
    public function testGetMethod(): void
    {
        $channel = new HttpClient();
        $httpMethod = 'GET';
        $channel->setMethod($httpMethod);

        $channelHttpMethod = $channel->getMethod();

        self::assertEquals($httpMethod, $channelHttpMethod);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::setHeaders
     */
    public function testSetHeaders(): void
    {
        $channel = new HttpClient();
        $header1 = TestResources::HEADER1;
        $header2 = TestResources::HEADER2;
        $value1 = TestResources::HEADER1_VALUE;
        $value2 = TestResources::HEADER2_VALUE;
        $headers = [$header1 => $value1, $header2 => $value2];

        $channel->setHeaders($headers);

        $channelHeaders = $channel->getHeaders();
        self::assertCount(3, $channelHeaders);
        self::assertEquals($value1, $channelHeaders[$header1]);
        self::assertEquals($value2, $channelHeaders[$header2]);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::getHeaders
     */
    public function testGetHeaders(): void
    {
        $channel = new HttpClient();
        $header1 = TestResources::HEADER1;
        $header2 = TestResources::HEADER2;
        $value1 = TestResources::HEADER1_VALUE;
        $value2 = TestResources::HEADER2_VALUE;
        $channel->setHeader($header1, $value1);
        $channel->setHeader($header2, $value2);

        $headers = $channel->getHeaders();

        self::assertCount(3, $headers);
        self::assertEquals($value1, $headers[$header1]);
        self::assertEquals($value2, $headers[$header2]);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::setHeader
     */
    public function testSetHeaderNewHeader(): void
    {
        $channel = new HttpClient();

        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER1_VALUE);

        $headers = $channel->getHeaders();
        self::assertCount(2, $headers);
        self::assertEquals(TestResources::HEADER1_VALUE, $headers[TestResources::HEADER1]);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::setHeader
     */
    public function testSetHeaderExistingHeaderReplace(): void
    {
        $channel = new HttpClient();
        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER1_VALUE);

        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER2_VALUE, true);

        $headers = $channel->getHeaders();
        self::assertCount(2, $headers);
        self::assertEquals(TestResources::HEADER2_VALUE, $headers[TestResources::HEADER1]);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::setHeader
     */
    public function testSetHeaderExistingHeaderAppend(): void
    {
        $channel = new HttpClient();
        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER1_VALUE);
        $expected = TestResources::HEADER1_VALUE . ', ' . TestResources::HEADER2_VALUE;

        $channel->setHeader(TestResources::HEADER1, TestResources::HEADER2_VALUE);

        $headers = $channel->getHeaders();
        self::assertCount(2, $headers);
        self::assertEquals($expected, $headers[TestResources::HEADER1]);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::sendAndGetHttpResponse
     * @throws GuzzleException
     */
    public function testSendAndGetHttpResponse(): void
    {
        $channel = new HttpClient();
        $url = new Url('https://example.com/');
        $channel->setExpectedStatusCode([200]);

        $response = $channel->sendAndGetHttpResponse([], $url);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::send
     * @throws GuzzleException
     */
    public function testSendSimple(): void
    {
        $channel = new HttpClient();
        $url = new Url('https://example.com/');
        $channel->setExpectedStatusCode([200]);

        $response = $channel->send([], $url);

        self::assertContains('<h1>Example Domain</h1>', $response);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::send
     * @throws GuzzleException
     */
    public function testSendWithOneFilter(): void
    {
        $channel = new HttpClient();
        $url = new Url('https://example.com/');
        $channel->setExpectedStatusCode(['200']);
        $expectedHeader = TestResources::HEADER1;
        $expectedResponseSubstring = TestResources::HEADER1_VALUE;
        $filter = new SimpleFilterMock($expectedHeader, $expectedResponseSubstring);
        $filters = [$filter];

        $response = $channel->send($filters, $url);

        self::assertArrayHasKey($expectedHeader, $channel->getHeaders());
        self::assertContains($expectedResponseSubstring, $response);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::send
     * @throws GuzzleException
     */
    public function testSendWithMultipleFilters(): void
    {
        $channel = new HttpClient();
        $url = new Url('https://example.com/');
        $channel->setExpectedStatusCode(['200']);
        $expectedHeader1 = TestResources::HEADER1;
        $expectedResponseSubstring1 = TestResources::HEADER1_VALUE;
        $expectedHeader2 = TestResources::HEADER2;
        $expectedResponseSubstring2 = TestResources::HEADER2_VALUE;
        $filter1 = new SimpleFilterMock($expectedHeader1, $expectedResponseSubstring1);
        $filter2 = new SimpleFilterMock($expectedHeader2, $expectedResponseSubstring2);
        $filters = [$filter1, $filter2];

        $response = $channel->send($filters, $url);

        self::assertArrayHasKey($expectedHeader1, $channel->getHeaders());
        self::assertArrayHasKey($expectedHeader2, $channel->getHeaders());
        self::assertContains('<h1>Example Domain</h1>', $response);
        self::assertContains($expectedResponseSubstring1, $response);
        self::assertContains($expectedResponseSubstring2, $response);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::send
     * @throws GuzzleException
     */
    public function testSendFail(): void
    {
        $channel = new HttpClient();
        $url = new Url('https://example.com/');
        $channel->setExpectedStatusCode(['201']);
        $this->expectException(get_class(new ServiceException(200)));

        $channel->send([], $url);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::setExpectedStatusCode
     */
    public function testSetSuccessfulStatusCodeSimple(): void
    {
        $channel = new HttpClient();
        $code = '200';

        $channel->setExpectedStatusCode([$code]);

        self::assertContains($code, $channel->getSuccessfulStatusCode());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::setExpectedStatusCode
     */
    public function testSetSuccessfulStatusCodeArray(): void
    {
        $channel = new HttpClient();
        $codes = ['200', '201', '202'];

        $channel->setExpectedStatusCode($codes);

        self::assertEquals($codes, $channel->getSuccessfulStatusCode());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::getSuccessfulStatusCode
     */
    public function testGetSuccessfulStatusCode(): void
    {
        $channel = new HttpClient();
        $codes = ['200', '201', '202'];
        $channel->setExpectedStatusCode($codes);

        $actualErrorCodes = $channel->getSuccessfulStatusCode();

        self::assertEquals($codes, $actualErrorCodes);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::setConfig
     */
    public function testSetConfig(): void
    {
        $channel = new HttpClient();
        $name = Resources::CONNECT_TIMEOUT;
        $value = 10;

        $channel->setConfig($name, $value);

        self::assertEquals($value, $channel->getConfig($name));
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::getConfig
     */
    public function testGetConfig(): void
    {
        $channel = new HttpClient();
        $name = Resources::CONNECT_TIMEOUT;
        $value = 10;
        $channel->setConfig($name, $value);

        $actualValue = $channel->getConfig($name);

        self::assertEquals($value, $actualValue);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::setBody
     */
    public function testSetBody(): void
    {
        $channel = new HttpClient();
        $expected = 'new body';

        $channel->setBody($expected);

        self::assertEquals($expected, $channel->getBody());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::getBody
     */
    public function testGetBody(): void
    {
        $channel = new HttpClient();
        $expected = 'new body';
        $channel->setBody($expected);

        $actual = $channel->getBody();

        self::assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::__clone
     */
    public function test__clone(): void
    {
        $channel = new HttpClient();
        $channel->setHeader('myheader', 'headervalue');
        $channel->setUrl(new Url('https://www.example.com'));

        $actual = clone $channel;
        $channel->setUrl(new Url('https://example.com/'));
        $channel->setHeader('headerx', 'valuex');

        if ($channel->getUrl() !== null && $actual->getUrl() !== null) {
            self::assertNotEquals($channel->getHeaders(), $actual->getHeaders());
            self::assertNotEquals($channel->getUrl()->getUrl(), $actual->getUrl()->getUrl());
            return;
        }

        self::assertTrue(false, 'Object value is null');
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\HttpClient::throwIfError
     */
    public function testThrowIfError(): void
    {
        $this->expectException(get_class(new ServiceException(200)));

        HttpClient::throwIfError(200, '', '', [10]);
    }
}
