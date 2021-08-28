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

use PHPUnit\Framework\TestCase;
use WindowsAzure\Common\Internal\Http\BatchRequest;
use WindowsAzure\Common\Internal\Http\HttpCallContext;

/**
 * Unit tests for class HttpCallContext.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class BatchRequestTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Http\batchRequest::appendContext
     * @covers \WindowsAzure\Common\Internal\Http\batchRequest::encode
     * @covers \WindowsAzure\Common\Internal\Http\batchRequest::getBody
     */
    public function testAppendContext(): void
    {
        $batchReq = new BatchRequest();
        $context = new HttpCallContext();
        $body = 'test body';
        $uri = 'https://www.someurl.com';
        $context->setBody($body);
        $context->setUri($uri);

        $batchReq->appendContext($context);
        $batchReq->encode();
        $resultBody = $batchReq->getBody();

        self::assertContains($body, $resultBody);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\BatchRequest::getHeaders
     */
    public function testGetHeaders(): void
    {
        $batchReq = new BatchRequest();
        $context = new HttpCallContext();
        $body = 'test body';
        $uri = 'https://www.someurl.com';
        $context->setBody($body);
        $context->setUri($uri);

        $batchReq->appendContext($context);
        $batchReq->encode();
        $resultHeader = $batchReq->getHeaders();

        self::assertCount(1, $resultHeader);
        self::assertContains('multipart/mixed', $resultHeader['Content-Type']);
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\batchRequest::getContexts
     */
    public function testGetContexts(): void
    {
        $batchReq = new BatchRequest();
        $context = new HttpCallContext();
        $batchReq->appendContext($context);

        $result = $batchReq->getContexts();

        self::assertEquals($context, $result[0]);
    }
}
