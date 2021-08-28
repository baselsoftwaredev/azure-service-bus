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

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\framework\TestResources;
use WindowsAzure\Common\Internal\Http\Url;
use WindowsAzure\Common\Internal\Resources;

/**
 * Unit tests for class Url.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class UrlTest extends TestCase
{
    private Url $url;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     *
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->url = new Url(TestResources::VALID_URL);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::__construct
     * @covers \WindowsAzure\Common\Internal\Http\Url::_setPathIfEmpty
     */
    public function test__construct(): void
    {
        self::assertEquals(TestResources::VALID_URL . '/', $this->url->getUrl());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::__construct
     * @throws Exception
     */
    public function test__constructEmptyUrlFail(): void
    {
        $urlString = '';
        $this->expectException(get_class(new InvalidArgumentException(Resources::INVALID_URL_MSG)));

        new Url($urlString);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::__construct
     * @throws Exception
     */
    public function test__constructNonStringUrlFail(): void
    {
        $urlString = '1';
        $this->expectException(get_class(new InvalidArgumentException(Resources::INVALID_URL_MSG)));

        new Url($urlString);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::__construct
     * @throws Exception
     */
    public function test__constructInvalidUrlFail(): void
    {
        $urlString = 'ww.invalidurl,com';
        $this->expectException(get_class(new InvalidArgumentException(Resources::INVALID_URL_MSG)));

        new Url($urlString);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::__construct
     * @throws Exception
     */
    public function test__constructWithUrlPath(): void
    {
        $urlString = TestResources::VALID_URL . '/';

        $url = new Url($urlString);

        self::assertEquals($urlString, $url->getUrl());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::getQuery
     */
    public function testGetQuery(): void
    {
        $expectedQueryString = TestResources::HEADER1 . '=' . TestResources::HEADER1_VALUE;
        $this->url->setQueryVariable(TestResources::HEADER1, TestResources::HEADER1_VALUE);

        $actualQueryString = $this->url->getQuery();

        self::assertEquals($expectedQueryString, $actualQueryString);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::getQueryVariables
     */
    public function testGetQueryVariables(): void
    {
        $expectedQueryVariables = [TestResources::HEADER1 => TestResources::HEADER1_VALUE];
        $this->url->setQueryVariable(TestResources::HEADER1, TestResources::HEADER1_VALUE);

        $actualQueryVariables = $this->url->getQueryVariables();

        self::assertEquals($expectedQueryVariables, $actualQueryVariables);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::setQueryVariable
     */
    public function testSetQueryVariable(): void
    {
        $expectedQueryVariables = [TestResources::HEADER1 => TestResources::HEADER1_VALUE];

        $this->url->setQueryVariable(TestResources::HEADER1, TestResources::HEADER1_VALUE);

        self::assertEquals($expectedQueryVariables, $this->url->getQueryVariables());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::setQueryVariable
     */
    public function testSetQueryVariableSetEmptyValue(): void
    {
        $key = 'validkey';

        $this->url->setQueryVariable($key, Resources::EMPTY_STRING);

        $queryVariables = $this->url->getQueryVariables();
        self::assertEquals(Resources::EMPTY_STRING, $queryVariables[$key]);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::getUrl
     * @covers \WindowsAzure\Common\Internal\Http\Url::_setPathIfEmpty
     */
    public function testGetUrl(): void
    {
        $actualUrl = $this->url->getUrl();

        self::assertEquals(TestResources::VALID_URL . '/', $actualUrl);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::setUrlPath
     */
    public function testSetUrlPath(): void
    {
        $urlPath = '/myqueue';

        $this->url->setUrlPath($urlPath);

        self::assertEquals($urlPath, parse_url($this->url->getUrl(), PHP_URL_PATH));
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::appendUrlPath
     */
    public function testAppendUrlPath(): void
    {
        $expectedUrlPath = '/myqueue';
        $urlPath = 'myqueue';

        $this->url->appendUrlPath($urlPath);

        self::assertEquals($expectedUrlPath, parse_url($this->url->getUrl(), PHP_URL_PATH));
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::__toString
     * @covers \WindowsAzure\Common\Internal\Http\Url::_setPathIfEmpty
     */
    public function test__toString(): void
    {
        $actualUrl = $this->url->__toString();

        self::assertEquals(TestResources::VALID_URL . '/', $actualUrl);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::__clone
     * @throws Exception
     */
    public function test__clone(): void
    {

        $actualUrl = clone $this->url;
        $this->url->setQueryVariable('key', 'value');

        self::assertNotEquals($this->url->getQueryVariables(), $actualUrl->getQueryVariables());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\Url::setQueryVariables
     */
    public function testSetQueryVariables(): void
    {
        $expectedQueryVariables = [
            TestResources::HEADER1 => TestResources::HEADER1_VALUE,
            TestResources::HEADER2 => TestResources::HEADER2_VALUE,
        ];

        $this->url->setQueryVariables($expectedQueryVariables);

        self::assertEquals($expectedQueryVariables, $this->url->getQueryVariables());
    }
}
