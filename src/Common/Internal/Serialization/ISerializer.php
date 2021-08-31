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

/**
 * The serialization interface.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
interface ISerializer
{
    /**
     * Serialize an object into a XML.
     *
     * @param object $targetObject The target object to be serialized
     * @param string $rootName     The name of the root
     * @return string
     */
    public static function objectSerialize(object $targetObject, string $rootName): string;

    /**
     * Serializes given array. The array indices must be string to use the as element name.
     *
     * @param array<mixed, mixed>        $array      The object to serialize represented in array
     * @param array<string, string>|null $properties The used properties in the serialization process
     * @return string
     */
    public function serialize(array $array, array $properties = null): string;

    /**
     * Unserializes given serialized string.
     *
     * @param string $serialized The serialized object in string representation
     * @return mixed
     */
    public function unserialize(string $serialized);
}
