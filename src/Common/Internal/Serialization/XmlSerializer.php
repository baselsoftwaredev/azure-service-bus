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

namespace WindowsAzure\Common\Internal\Serialization;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use SimpleXMLElement;
use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\Internal\Utilities;
use XMLWriter;

/**
 * Short description.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class XmlSerializer implements ISerializer
{
    const STANDALONE = 'standalone';
    const ROOT_NAME = 'rootName';
    const DEFAULT_TAG = 'defaultTag';

    /**
     * Serialize an object with specified root element name.
     *
     * @param object $targetObject The target object
     * @param string $rootName     The name of the root element
     * @return string
     * @throws ReflectionException
     */
    public static function objectSerialize(object $targetObject, string $rootName): string
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);
        $reflectionClass = new ReflectionClass($targetObject);
        $methodArray = $reflectionClass->getMethods();
        $attributes = self::_getInstanceAttributes(
            $targetObject,
            $methodArray
        );

        $xmlWriter->startElement($rootName);
        if (! is_null($attributes)) {
            foreach (array_keys($attributes) as $attributeKey) {
                $xmlWriter->writeAttribute(
                    (string) $attributeKey,
                    $attributes[$attributeKey]
                );
            }
        }

        foreach ($methodArray as $method) {
            if ((strpos($method->name, 'get') === 0)
                && $method->isPublic()
                && ($method->name != 'getAttributes')
            ) {
                $variableName = substr($method->name, 3);
                $variableValue = $method->invoke($targetObject);
                if (! empty($variableValue)) {
                    if (gettype($variableValue) === 'object') {
                        $xmlWriter->writeRaw(
                            self::objectSerialize(
                                $variableValue, $variableName
                            )
                        );
                    } else {
                        $xmlWriter->writeElement($variableName, $variableValue);
                    }
                }
            }
        }
        $xmlWriter->endElement();

        return $xmlWriter->outputMemory(true);
    }

    /**
     * Gets the attributes of a specified object if getAttributes method is exposed.
     *
     * @param object                       $targetObject The target object
     * @param array<int, ReflectionMethod> $methodArray  The array of method of the target object
     * @return mixed
     * @throws ReflectionException
     */
    private static function _getInstanceAttributes(object $targetObject, array $methodArray)
    {
        foreach ($methodArray as $method) {
            if ($method->name == 'getAttributes') {
                return $method->invoke($targetObject);
            }
        }

        return null;
    }

    /**
     * Serializes given array. The array indices must be string to use them as element name.
     *
     * @param array<mixed, mixed>        $array      The object to serialize represented in array
     * @param array<string, string>|null $properties The used properties in the serialization process
     * @return string
     */
    public function serialize(array $array, array $properties = null): string
    {
        $xmlVersion = '1.0';
        $xmlEncoding = 'UTF-8';
        $standalone = Utilities::tryGetValue($properties, self::STANDALONE);
        $defaultTag = Utilities::tryGetValue($properties, self::DEFAULT_TAG);
        $rootName = Utilities::tryGetValue($properties, self::ROOT_NAME);
        $docNamespace = Utilities::tryGetValue(
            $array,
            Resources::XTAG_NAMESPACE,
        );

        $xmlW = new XMLWriter();
        $xmlW->openMemory();
        $xmlW->setIndent(true);
        $xmlW->startDocument($xmlVersion, $xmlEncoding, $standalone);

        if (is_null($docNamespace)) {
            $xmlW->startElement($rootName);
        } else {
            foreach ($docNamespace as $uri => $prefix) {
                $xmlW->startElementNs($prefix, $rootName, $uri);
                break;
            }
        }

        unset($array[Resources::XTAG_NAMESPACE]);
        self::_arr2xml($xmlW, $array, $defaultTag);

        $xmlW->endElement();

        return $xmlW->outputMemory(true);
    }

    /**
     * Takes an array and produces XML based on it.
     *
     * @param XMLWriter           $xmlW         XMLWriter object that was previously instantiated and is used for
     *                                          creating the XML
     * @param array<mixed, mixed> $data         Array to be converted to XML
     * @param string|null         $defaultTag   Default XML tag to be used if none specified
     */
    private function _arr2xml(XMLWriter $xmlW, array $data, string $defaultTag = null): void
    {
        foreach ($data as $key => $value) {
            if ($key === Resources::XTAG_ATTRIBUTES) {
                foreach ($value as $attributeName => $attributeValue) {
                    $xmlW->writeAttribute($attributeName, $attributeValue);
                }
            } elseif (is_array($value)) {
                if (! is_int($key)) {
                    if ($key !== Resources::EMPTY_STRING) {
                        $xmlW->startElement($key);
                    } else {
                        if ($defaultTag !== null) {
                            $xmlW->startElement($defaultTag);
                        }
                    }
                }

                $this->_arr2xml($xmlW, $value);

                if (! is_int($key)) {
                    $xmlW->endElement();
                }
            } else {
                if (! is_int($key)) {
                    $xmlW->writeElement($key, $value);

                }
            }
        }
    }

    /**
     * Unserializes given serialized string.
     *
     * @param string $serialized The serialized object in string representation
     * @return array<mixed, mixed>
     * @throws Exception
     */
    public function unserialize(string $serialized): array
    {
        $sXml = new SimpleXMLElement($serialized);

        return $this->_sxml2arr($sXml);
    }

    /**
     * Converts a SimpleXML object to an Array recursively
     * ensuring all sub-elements are arrays as well.
     *
     * @param array<mixed, mixed>|SimpleXMLElement $sXml The SimpleXML object
     * @return array<mixed, mixed>
     * @throws Exception
     */
    private function _sxml2arr($sXml): array
    {
        $arr = null;

        foreach ((array) $sXml as $key => $value) {
            if (is_a($value, SimpleXMLElement::class) || (is_array($value))) {
                $arr[$key] = $this->_sxml2arr($value);
            } else {
                $arr[$key] = $value;
            }
        }

        if ($arr === null) {
            throw new Exception('Failed converting SimpleXML object to array');
        }

        return $arr;
    }
}
