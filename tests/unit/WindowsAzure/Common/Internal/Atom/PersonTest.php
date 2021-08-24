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
use WindowsAzure\Common\Internal\Atom\Person;

/**
 * Unit tests for class Person.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class PersonTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Person::__construct
     */
    public function testPersonConstructor(): void
    {
        $feed = new Person();

        self::assertNotNull($feed);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Person::getName
     * @covers \WindowsAzure\Common\Internal\Atom\Person::setName
     */
    public function testGetSetName(): void
    {
        $expected = 'testName';
        $person = new Person();

        $person->setName($expected);
        $actual = $person->getName();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Person::getUri
     * @covers \WindowsAzure\Common\Internal\Atom\Person::setUri
     */
    public function testGetSetUri(): void
    {
        $expected = 'testUri';
        $person = new Person();

        $person->setUri($expected);
        $actual = $person->getUri();

        self::assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Person::getEmail
     * @covers \WindowsAzure\Common\Internal\Atom\Person::setEmail
     */
    public function testGetSetEmail(): void
    {
        $expected = 'testEmail';
        $person = new Person();

        $person->setEmail($expected);
        $actual = $person->getEmail();

        self::assertEquals(
            $expected,
            $actual
        );
    }
}
