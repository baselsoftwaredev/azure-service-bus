<?php

/**
 * LICENSE: Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP version 5
 *
 * @category  Microsoft
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 *
 * @link      https://github.com/windowsazure/azure-sdk-for-php
 */

namespace Tests\framework;

use Dotenv\Dotenv;
use Exception;
use WindowsAzure\Common\Internal\Resources;

/**
 * Resources for testing framework.
 *
 * @author     Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright  2012 Microsoft Corporation
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 *
 * @version   Release: 0.5.0_2016-11
 *
 * @link       https://github.com/windowsazure/azure-sdk-for-php
 */
class TestResources
{
    const QUEUE1_NAME = 'Queue1';
    const KEY4 = 'AhlzsbLRkjfwObuqff3xrhB2yWJNh1EMptmcmxFJ6fvPTVX3PZXwrG2YtYWf5DPMVgNsteKStM5iBLlknYFVoA==';
    const ACCOUNT_NAME = 'myaccount';
    const QUEUE_URI = '.queue.core.windows.net';
    const URI1 = 'http://myaccount.queue.core.windows.net/myqueue';
    const URI2 = 'http://myaccount.queue.core.windows.net/?comp=list';
    const DATE1 = 'Sat, 18 Feb 2012 16:25:21 GMT';
    const DATE2 = 'Mon, 20 Feb 2012 17:12:31 GMT';
    const VALID_URL = 'http://www.example.com';
    const HEADER1 = 'testheader1';
    const HEADER2 = 'testheader2';
    const HEADER1_VALUE = 'HeaderValue1';
    const HEADER2_VALUE = 'HeaderValue2';

    // See https://tools.ietf.org/html/rfc2616
    const STATUS_BAD_REQUEST = 400;
    const STATUS_INTERNAL_SERVER_ERROR = 500;

    public static function getServiceBusConnectionString()
    {
        $dotenv = Dotenv::create(__DIR__ . '/../../');
        $dotenv->load();
        $connectionString = $_ENV['AZURE_SERVICE_BUS_CONNECTION_STRING'];

        if (empty($connectionString)) {
            throw new Exception('AZURE_SERVICE_BUS_CONNECTION_STRING environment variable is missing.');
        }

        return $connectionString;
    }

    public static function getServicePropertiesSample()
    {
        $sample = [];
        $sample['Logging']['Version'] = '1.0';
        $sample['Logging']['Delete'] = 'true';
        $sample['Logging']['Read'] = 'false';
        $sample['Logging']['Write'] = 'true';
        $sample['Logging']['RetentionPolicy']['Enabled'] = 'true';
        $sample['Logging']['RetentionPolicy']['Days'] = '20';
        $sample['Metrics']['Version'] = '1.0';
        $sample['Metrics']['Enabled'] = 'true';
        $sample['Metrics']['IncludeAPIs'] = 'false';
        $sample['Metrics']['RetentionPolicy']['Enabled'] = 'true';
        $sample['Metrics']['RetentionPolicy']['Days'] = '20';

        return $sample;
    }

    public static function getTestOAuthAccessToken()
    {
        return [
            Resources::OAUTH_ACCESS_TOKEN => 'http%3a%2f%2fschemas.xmlsoap.org%2fws%2f2005%2f05%2fidentity%2fclaims%2fnameidentifier=client_id&http%3a%2f%2fschemas.microsoft.com%2f'
                .'accesscontrolservice%2f2010%2f07%2fclaims%2fidentityprovider=https%3a%2f%2fwamsprodglobal001acs.accesscontrol.windows.net%2f&Audience=urn%3aWindows'
                .'AzureMediaServices&ExpiresOn=1326498007&Issuer=https%3a%2f%2f wamsprodglobal001acs.accesscontrol.windows.net%2f&HMACSHA256=hV1WF7sTe%2ffoHqzK%2ftm'
                .'nwQY22NRPaDytcOOpC9Nv4DA%3d","token_type":"http://schemas.xmlsoap.org/ws/2009/11/swt-token-profile-1.0',
            Resources::OAUTH_EXPIRES_IN => '3599',
            Resources::OAUTH_SCOPE => 'urn:WindowsAzureMediaServices',
        ];
    }

    public static function getSimpleJson()
    {
        $data['dataArray'] = ['test1', 'test2', 'test3'];
        $data['jsonArray'] = '["test1","test2","test3"]';

        $data['dataObject'] = ['k1' => 'test1', 'k2' => 'test2', 'k3' => 'test3'];
        $data['jsonObject'] = '{"k1":"test1","k2":"test2","k3":"test3"}';

        return $data;
    }
}
