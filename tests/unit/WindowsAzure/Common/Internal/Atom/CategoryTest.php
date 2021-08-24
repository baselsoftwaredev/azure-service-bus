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
use WindowsAzure\Common\Internal\Atom\Category;
use XMLWriter;

/**
 * Unit tests for class Category.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-vbus
 * @version   Release: 0.5.0_2016-11
 * @category  Microsoft
 */
class CategoryTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::__construct
     */
    public function testCategoryConstructor()
    {
        $category = new Category();

        $this->assertNotNull($category);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::__construct
     */
    public function testCategoryConstructorWithParameterSuccess()
    {
        $expectedUndefinedContent = 'testCategoryConstructorWithParameterSuccess';

        $category = new Category($expectedUndefinedContent);
        $actualUndefinedContent = $category->getUndefinedContent();

        $this->assertEquals(
            $expectedUndefinedContent,
            $actualUndefinedContent
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getTerm
     * @covers \WindowsAzure\Common\Internal\Atom\Category::setTerm
     */
    public function testCategoryGetSetTerm()
    {
        $expectedTerm = 'testCategoryGetSetTerm';
        $category = new Category();

        $category->setTerm($expectedTerm);
        $actualTerm = $category->getTerm();

        $this->assertEquals(
            $expectedTerm,
            $actualTerm
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getScheme
     * @covers \WindowsAzure\Common\Internal\Atom\Category::setScheme
     */
    public function testCategoryGetSetScheme()
    {
        $expectedScheme = 'testCategoryGetSetScheme';
        $category = new Category();

        $category->setScheme($expectedScheme);
        $actualScheme = $category->getScheme();

        $this->assertEquals(
            $expectedScheme,
            $actualScheme
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getLabel
     * @covers \WindowsAzure\Common\Internal\Atom\Category::setLabel
     */
    public function testCategoryGetSetLabel()
    {
        $expectedLabel = 'testCategoryGetSetLabel';
        $category = new Category();

        $category->setLabel($expectedLabel);
        $actualLabel = $category->getLabel();

        $this->assertEquals(
            $expectedLabel,
            $actualLabel
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getUndefinedContent
     * @covers \WindowsAzure\Common\Internal\Atom\Category::setUndefinedContent
     */
    public function testCategoryGetSetUndefinedContent()
    {
        $expectedUndefinedContent = 'testCategoryGetSetUndefinedContent';
        $category = new Category();

        $category->setUndefinedContent($expectedUndefinedContent);
        $actualUndefinedContent = $category->getUndefinedContent();

        $this->assertEquals(
            $expectedUndefinedContent,
            $actualUndefinedContent
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::parseXml
     */
    public function testCategoryCreate()
    {
        $xml = '<category/>';

        $category = new Category();
        $category->parseXml($xml);

        $this->assertNotNull($category);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::parseXml
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getTerm
     */
    public function testCategoryCreateWithTerm()
    {
        $xml = '<category term="testTerm"></category>';
        $expected = 'testTerm';

        $category = new Category();
        $category->parseXml($xml);
        $actual = $category->getTerm();

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::parseXml
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getScheme
     */
    public function testCategoryCreateWithScheme()
    {
        $xml = '<category scheme="testScheme"></category>';
        $expected = 'testScheme';

        $category = new Category();
        $category->parseXml($xml);
        $actual = $category->getScheme();

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::parseXml
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getLabel
     */
    public function testCategoryCreateWithLabel()
    {
        $xml = '<category label="testLabel"></category>';
        $expected = 'testLabel';

        $category = new Category();
        $category->parseXml($xml);
        $actual = $category->getLabel();

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::parseXml
     * @covers \WindowsAzure\Common\Internal\Atom\Category::writeXml
     */
    public function testCategoryWriteEmptyXml()
    {
        $category = new Category();
        $expected = '<atom:category xmlns:atom="http://www.w3.org/2005/Atom"></atom:category>';

        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $category->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::parseXml
     * @covers \WindowsAzure\Common\Internal\Atom\Category::writeXml
     */
    public function testCategoryWriteXmlSuccess()
    {
        $category = new Category();
        $expected = '<atom:category term="testTerm" scheme="testScheme" label="testLabel" xmlns:atom="http://www.w3.org/2005/Atom"></atom:category>';
        $category->setTerm('testTerm');
        $category->setScheme('testScheme');
        $category->setLabel('testLabel');

        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $category->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getTerm
     * @covers \WindowsAzure\Common\Internal\Atom\Category::setTerm
     */
    public function testGetSetTerm()
    {
        $expected = 'testTerm';
        $category = new Category();

        $category->setTerm($expected);
        $actual = $category->getTerm();

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getScheme
     * @covers \WindowsAzure\Common\Internal\Atom\Category::setScheme
     */
    public function testGetSetScheme()
    {
        $expected = 'testScheme';
        $category = new Category();

        $category->setScheme($expected);
        $actual = $category->getScheme();

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getLabel
     * @covers \WindowsAzure\Common\Internal\Atom\Category::setLabel
     */
    public function testGetSetLabel()
    {
        $expected = 'testLabel';
        $category = new Category();

        $category->setLabel($expected);
        $actual = $category->getLabel();

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::getUndefinedContent
     * @covers \WindowsAzure\Common\Internal\Atom\Category::setUndefinedContent
     */
    public function testGetSetUndefinedContent()
    {
        $expected = 'testUndefinedContent';
        $category = new Category();

        $category->setUndefinedContent($expected);
        $actual = $category->getUndefinedContent();

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::parseXml
     */
    public function testCategoryParseXmlSuccess()
    {
        $expected = new Category();
        $xml = '<category/>';
        $actual = new Category();

        $actual->parseXml($xml);

        $this->assertEquals(
            $expected,
            $actual
        );
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Atom\Category::writeXml
     */
    public function testCategoryWriteXmlSuccessAllProperties()
    {
        $category = new Category();
        $category->setTerm('testTerm');
        $category->setScheme('testScheme');
        $category->setLabel('testLabel');
        $category->setUndefinedContent('testUndefinedContent');
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $expected = '<atom:category term="testTerm" scheme="testScheme" label="testLabel" xmlns:atom="http://www.w3.org/2005/Atom">testUndefinedContent</atom:category>';

        $category->writeXml($xmlWriter);
        $actual = $xmlWriter->outputMemory();

        $this->assertEquals(
            $expected,
            $actual
        );
    }
}
