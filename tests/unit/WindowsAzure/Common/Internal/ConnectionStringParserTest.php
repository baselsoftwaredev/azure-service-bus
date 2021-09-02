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

namespace Tests\unit\WindowsAzure\Common\Internal;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use WindowsAzure\Common\Internal\ConnectionStringParser;

/**
 * Unit tests for class ConnectionStringParser.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class ConnectionStringParserTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::parseConnectionString
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::__construct
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_parse
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_createException
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_skipWhiteSpaces
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractKey
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractString
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_skipOperator
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractValue
     */
    public function testKeyNames(): void
    {
        $this->_parseTest('a=b', 'a', 'b');
        $this->_parseTest(' a =b; c = d', 'a', 'b', 'c', 'd');
        $this->_parseTest('a b=c', 'a b', 'c');
        $this->_parseTest("'a b'=c", 'a b', 'c');
        $this->_parseTest('"a b"=c', 'a b', 'c');
        $this->_parseTest('"a=b"=c', 'a=b', 'c');
        $this->_parseTest('a=b=c', 'a', 'b=c');
        $this->_parseTest("'a='=b", 'a=', 'b');
        $this->_parseTest('"a="=b', 'a=', 'b');
        $this->_parseTest("\"a'b\"=c", "a'b", 'c');
        $this->_parseTest("'a\"b'=c", 'a"b', 'c');
        $this->_parseTest("a'b=c", "a'b", 'c');
        $this->_parseTest('a"b=c', 'a"b', 'c');
        $this->_parseTest("a'=b", "a'", 'b');
        $this->_parseTest('a"=b', 'a"', 'b');
    }

    private function _parseTest($connectionString)
    {
        // Setup
        $arguments = func_get_args();
        $count = func_num_args();
        $expected = [];
        for ($i = 1; $i < $count; $i += 2) {
            $expected[$arguments[$i]] = $arguments[$i + 1];
        }

        // Test
        $actual = ConnectionStringParser::parseConnectionString('connectionString', $connectionString);

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::parseConnectionString
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::__construct
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_parse
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_createException
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_skipWhiteSpaces
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractKey
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractString
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_skipOperator
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractValue
     */
    public function testAssignments(): void
    {
        $this->_parseTest('a=b', 'a', 'b');
        $this->_parseTest('a = b', 'a', 'b');
        $this->_parseTest('a==b', 'a', '=b');
    }

    /**
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::parseConnectionString
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::__construct
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_parse
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_createException
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_skipWhiteSpaces
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractKey
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractString
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_skipOperator
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractValue
     */
    public function testValues(): void
    {
        $this->_parseTest('a=b', 'a', 'b');
        $this->_parseTest('a= b ', 'a', 'b');
        $this->_parseTest('a= b ;c= d;', 'a', 'b', 'c', 'd');
        $this->_parseTest('a=', 'a', '');
        $this->_parseTest('a=;', 'a', '');
        $this->_parseTest('a=;b=', 'a', '', 'b', '');
        $this->_parseTest('a==b', 'a', '=b');
        $this->_parseTest('a=b=;c==d=', 'a', 'b=', 'c', '=d=');
        $this->_parseTest("a='b c'", 'a', 'b c');
        $this->_parseTest('a="b c"', 'a', 'b c');
        $this->_parseTest("a=\"b'c\"", 'a', "b'c");
        $this->_parseTest("a='b\"c'", 'a', 'b"c');
        $this->_parseTest("a='b=c'", 'a', 'b=c');
        $this->_parseTest('a="b=c"', 'a', 'b=c');
        $this->_parseTest("a='b;c=d'", 'a', 'b;c=d');
        $this->_parseTest('a="b;c=d"', 'a', 'b;c=d');
        $this->_parseTest("a='b c' ", 'a', 'b c');
        $this->_parseTest('a="b c" ', 'a', 'b c');
        $this->_parseTest("a=b'c", 'a', "b'c");
        $this->_parseTest('a=b"c', 'a', 'b"c');
        $this->_parseTest("a=b'", 'a', "b'");
        $this->_parseTest('a=b"', 'a', 'b"');
    }

    /**
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::parseConnectionString
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::__construct
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_parse
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_createException
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_skipWhiteSpaces
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractKey
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractString
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_skipOperator
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractValue
     */
    public function testSeparators(): void
    {
        $this->_parseTest('a=b;', 'a', 'b');
        $this->_parseTest('a=b', 'a', 'b');
        $this->_parseTest('a=b;c=d', 'a', 'b', 'c', 'd');
        $this->_parseTest('a=b;c=d;', 'a', 'b', 'c', 'd');
        $this->_parseTest('a=b ; c=d', 'a', 'b', 'c', 'd');
    }

    /**
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::parseConnectionString
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::__construct
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_parse
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_createException
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_skipWhiteSpaces
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractKey
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractString
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_skipOperator
     * @covers \WindowsAzure\Common\Internal\ConnectionStringParser::_extractValue
     */
    public function testInvalidInputFail(): void
    {
        $this->_parseTestFail(';');           // Separator without an assignment;
        $this->_parseTestFail('=b');          // Missing key name;
        $this->_parseTestFail("''=b");        // Empty key name;
        $this->_parseTestFail('""=b');        // Empty key name;
        $this->_parseTestFail('test');        // Missing assignment;
        $this->_parseTestFail(';a=b');        // Separator without key=value;
        $this->_parseTestFail('a=b;;');       // Two separators at the end;
        $this->_parseTestFail('a=b;;c=d');    // Two separators in the middle.
        $this->_parseTestFail("'a=b");        // Runaway single-quoted string at the beginning of the key name;
        $this->_parseTestFail('"a=b');       // Runaway double-quoted string at the beginning of the key name;
        $this->_parseTestFail("'=b");         // Runaway single-quoted string in key name;
        $this->_parseTestFail('"=b');        // Runaway double-quoted string in key name;
        $this->_parseTestFail("a='b");        // Runaway single-quoted string in value;
        $this->_parseTestFail('a="b');       // Runaway double-quoted string in value;
        $this->_parseTestFail("a='b'c");      // Extra character after single-quoted value;
        $this->_parseTestFail('a="b"c');    // Extra character after double-quoted value;
        $this->_parseTestFail("'a'b=c");      // Extra character after single-quoted key;
        $this->_parseTestFail('"a"b=c');    // Extra character after double-quoted key;
    }

    private function _parseTestFail($value): void
    {
        // Setup
        $this->expectException(RuntimeException::class);

        // Test
        ConnectionStringParser::parseConnectionString('connectionString', $value);
    }
}
