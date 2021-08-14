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

namespace WindowsAzure\Common;

use WindowsAzure\Common\Internal\ConnectionStringSource;
use WindowsAzure\Common\Internal\Utilities;
use WindowsAzure\Common\Internal\Validate;

/**
 * Configuration manager for accessing Windows Azure settings.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class CloudConfigurationManager
{
    /**
     * @var bool
     */
    private static bool $_isInitialized = false;

    /**
     * The list of connection string sources.
     *
     * @var array<callable>
     */
    private static $_sources;

    /**
     * Initializes the connection string source providers.
     */
    private static function _init(): void
    {
        if (! self::$_isInitialized) {
            self::$_sources = [];

            // Get list of default connection string sources.
            $default = ConnectionStringSource::getDefaultSources();
            foreach ($default as $name => $provider) {
                self::$_sources[$name] = $provider;
            }

            self::$_isInitialized = true;
        }
    }

    /**
     * Gets a connection string from all available sources.
     *
     * @param string $key The connection string key name
     * @return null|string If the key does not exist return null
     */
    public static function getConnectionString(string $key): ?string
    {
        Validate::isString($key, 'key');

        self::_init();
        $value = null;

        foreach (self::$_sources as $source) {
            $value = call_user_func_array($source, [$key]);

            if (is_string($value)) {
                break;
            }
        }

        return $value;
    }

    /**
     * Registers a new connection string source provider. If the source to get
     * registered is a default source, only the name of the source is required.
     *
     * @param string        $name     The source name
     * @param callable|null $provider The source callback
     * @param bool          $prepend  The $provider is processed first when calling getConnectionString. When false the
     *                                $provider is processed after the existing callbacks.
     */
    public static function registerSource(string $name, callable $provider = null, bool $prepend = false): void
    {
        Validate::isString($name, 'name');
        Validate::notNullOrEmpty($name, 'name');

        self::_init();
        $default = ConnectionStringSource::getDefaultSources();

        // Try to get callback if the user is trying to register a default source.
        $provider = Utilities::tryGetValue($default, $name, $provider);

        Validate::notNullOrEmpty($provider, 'callback');

        if ($prepend) {
            self::$_sources = array_merge(
                [$name => $provider],
                self::$_sources
            );
        } else {
            self::$_sources[$name] = $provider;
        }
    }

    /**
     * Unregisters a connection string source.
     *
     * @param string $name The source name
     * @return callable
     */
    public static function unregisterSource(string $name): callable
    {
        Validate::isString($name, 'name');
        Validate::notNullOrEmpty($name, 'name');

        self::_init();

        $sourceCallback = Utilities::tryGetValue(self::$_sources, $name);

        if (! is_null($sourceCallback)) {
            unset(self::$_sources[$name]);
        }

        return $sourceCallback;
    }
}
