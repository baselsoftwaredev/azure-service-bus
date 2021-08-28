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

use PHPUnit\Framework\TestCase;
use WindowsAzure\Common\Internal\Http\HttpCallContext;
use WindowsAzure\Common\Internal\Http\Url;

/**
 * Unit tests for class HttpCallContext.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class HttpCallContextTest extends TestCase
{
    private HttpCallContext $context;

    /**
     * Sets up the fixture
     * This method is called before a test is executed.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->context = new HttpCallContext();
    }

    /**
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::getMethod
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::setMethod
     */
    public function testSetMethod(): void
    {
        $expected = 'Method';

        $this->context->setMethod($expected);

        self::assertEquals($expected, $this->context->getMethod());
    }

    /**
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::getBody
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::setBody
     */
    public function testSetBody(): void
    {
        $expected = 'Body';

        $this->context->setBody($expected);

        self::assertEquals($expected, $this->context->getBody());
    }

    /**
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::getPath
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::setPath
     */
    public function testSetPath(): void
    {
        $expected = 'Path';

        $this->context->setPath($expected);

        self::assertEquals($expected, $this->context->getPath());
    }

    /**
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::getUri
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::setUri
     */
    public function testSetUri(): void
    {
        $expected = new Url('https://www.microsoft.com');

        $this->context->setUri($expected);

        self::assertEquals($expected, $this->context->getUri());
    }

    /**
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::getHeaders
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::setHeaders
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::addHeader
     */
    public function testSetHeaders(): void
    {
        $expected = ['h1' => 'value1', 'h2' => 'value2', 'h3' => 'value3'];

        $this->context->setHeaders($expected);

        self::assertEquals($expected, $this->context->getHeaders());
    }

    /**
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::getQueryParameters
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::setQueryParameters
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::addQueryParameter
     */
    public function testSetQueryParameters(): void
    {
        $expected = ['h1' => 'value1', 'h2' => 'value2', 'h3' => 'value3'];

        $this->context->setQueryParameters($expected);

        self::assertEquals($expected, $this->context->getQueryParameters());
    }

    /**
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::getStatusCodes
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::setStatusCodes
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::addStatusCode
     */
    public function testSetStatusCodes(): void
    {
        $expected = ['1', '2', '3'];

        $this->context->setStatusCodes($expected);

        self::assertEquals($expected, $this->context->getStatusCodes());
    }

    /**
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::getHeader
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::addHeader
     */
    public function testAddHeader(): void
    {
        $expected = 'value';
        $key = 'key';

        $this->context->addHeader($key, $expected);

        self::assertEquals($expected, $this->context->getHeader($key));
    }

    /**
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::removeHeader
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::getHeaders
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::addHeader
     */
    public function testRemoveHeader(): void
    {

        $value = 'value';
        $key = 'key';
        $this->context->addHeader($key, $value);

        $this->context->removeHeader($key);

        self::assertArrayNotHasKey($key, $this->context->getHeaders());
    }

    /**
     * @covers  \WindowsAzure\Common\Internal\Http\HttpCallContext::__toString
     */
    public function test__toString(): void
    {
        $headers = ['h1' => 'v1', 'h2' => 'v2'];
        $method = 'GET';
        $uri = 'https://microsoft.com';
        $path = 'windowsazure/services';
        $body = 'The request body';
        $expected = "GET https://microsoft.com/windowsazure/services HTTP/1.1\nh1: v1\nh2: v2\n\nThe request body";
        $this->context->setHeaders($headers);
        $this->context->setMethod($method);
        $this->context->setUri($uri);
        $this->context->setPath($path);
        $this->context->setBody($body);

        $actual = $this->context->__toString();

        self::assertEquals($expected, $actual);
    }
}
