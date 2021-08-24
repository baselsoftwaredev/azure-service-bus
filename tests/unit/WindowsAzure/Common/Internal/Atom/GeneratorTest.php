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

namespace Tests\unit\WindowsAzure\Common\Internal\Atom;

use PHPUnit\Framework\TestCase;
use WindowsAzure\Common\Internal\Atom\Generator;

/**
 * Unit tests for class Generator.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/WindowsAzure/azure-sdk-for-php
 * @version   Release: 0.5.0_2016-11
 * @category  Microsoft
 */
class GeneratorTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::__construct
     */
    public function testGeneratorConstructor(): void
    {
        $expectedText = 'testGenerator';

        $generator = new Generator($expectedText);
        $actualText = $generator->getText();

        self::assertNotNull($generator);
        self::assertEquals(
            $expectedText,
            $actualText
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::getText
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::setText
     */
    public function testGeneratorGetSetText(): void
    {
        $expectedText = 'testGetText';
        $generator = new Generator();

        $generator->setText($expectedText);
        $actualText = $generator->getText();

        self::assertEquals(
            $expectedText,
            $actualText
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::getUri
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::setUri
     */
    public function testGeneratorGetSetUri(): void
    {
        $expectedUri = 'testGetSetUri';
        $generator = new Generator();

        $generator->setUri($expectedUri);
        $actualUri = $generator->getUri();

        self::assertEquals(
            $expectedUri,
            $actualUri
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::getVersion
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::setVersion
     */
    public function testGeneratorGetSetVersion(): void
    {
        $expectedVersion = 'testGetSetVersion';
        $generator = new Generator();

        $generator->setVersion($expectedVersion);
        $actualVersion = $generator->getVersion();

        self::assertEquals(
            $expectedVersion,
            $actualVersion
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::getText
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::setText
     */
    public function testGetSetText(): void
    {
        $expected = 'testText';
        $generator = new Generator();

        $generator->setText($expected);
        $actual = $generator->getText();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::getUri
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::setUri
     */
    public function testGetSetUri(): void
    {
        $expected = 'testUri';
        $generator = new Generator();

        $generator->setUri($expected);
        $actual = $generator->getUri();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::getVersion
     * @covers \WindowsAzure\Common\Internal\Atom\Generator::setVersion
     */
    public function testGetSetVersion(): void
    {
        $expected = 'testVersion';
        $generator = new Generator();

        $generator->setVersion($expected);
        $actual = $generator->getVersion();

        self::assertEquals(
            $expected,
            $actual
        );
    }
}
