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
use stdClass;

/**
 * Perform JSON serialization / deserialization.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class JsonSerializer implements ISerializer
{
    /**
     * Serialize an object with specified root element name.
     *
     * @param object $targetObject The target object
     * @param string $rootName     The name of the root element
     * @return string
     * @throws Exception
     */
    public static function objectSerialize(object $targetObject, string $rootName): string
    {
        $container = new stdClass();

        $container->$rootName = $targetObject;

        $encodedObject = json_encode($container);

        if ($encodedObject === false) {
            throw new Exception('PHP json_encode() failure to encode object');
        }

        return $encodedObject;
    }

    /**
     * Serializes given array. The array indices must be string to use them as element name.
     *
     * @param array<mixed, mixed>        $array      The object to serialize represented in array
     * @param array<string, string>|null $properties The used properties in the serialization process
     * @return string
     * @throws Exception
     */
    public function serialize(array $array, array $properties = null): string
    {
        $encodedArray = json_encode($array);

        if ($encodedArray === false) {
            throw new Exception('PHP json_encode() failure to encode object');
        }

        return $encodedArray;
    }

    /**
     * Unserializes given serialized string to array.
     *
     * @param string $serialized The serialized object in string representation
     * @return mixed
     * @throws Exception
     */
    public function unserialize(string $serialized)
    {
        $json = json_decode($serialized);
        if (is_object($json)) {
            return get_object_vars($json);
        }

        return $json;
    }
}
