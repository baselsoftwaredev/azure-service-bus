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

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use WindowsAzure\Common\Internal\Http\BatchRequest;
use WindowsAzure\Common\Internal\Http\BatchResponse;
use WindowsAzure\Common\Internal\Http\HttpCallContext;
use WindowsAzure\Common\ServiceException;

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
class BatchResponseTest extends TestCase
{
    /**
     * @covers \WindowsAzure\Common\Internal\Http\BatchResponse::__construct
     * @covers \WindowsAzure\Common\Internal\Http\BatchResponse::getResponses
     */
    public function test__construct(): void
    {
        // Setup
        $body = 'test response body';
        $encodedBody =
            "--batch_956c339e-1ef0-4443-9276-68c12888a3f7\r\n" .
            "Content-Type: multipart/mixed; boundary=changeset_4a3f1712-c034-416e-9772-905d28c0b122\r\n" .
            "\r\n" .
            "--changeset_4a3f1712-c034-416e-9772-905d28c0b122\r\n" .
            "Content-Transfer-Encoding: binary\r\n" .
            "Content-Type: application/http\r\n" .
            "\r\n" .
            "HTTP/1.1 200 OK\r\n" .
            "content-id: 1\r\n" .
            "\r\n" .
            $body .
            "--changeset_4a3f1712-c034-416e-9772-905d28c0b122--\r\n" .
            '--batch_956c339e-1ef0-4443-9276-68c12888a3f7--';
        $response = new Response(
            200,
            ['content-type' => ['boundary=batch_956c339e-1ef0-4443-9276-68c12888a3f7']],
            $encodedBody);

        // Test
        $batchResp = new BatchResponse($response);
        $result = $batchResp->getResponses();

        // Assert
        self::assertCount(1, $result);
        self::assertEquals($body, $result[0]->getBody());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\BatchResponse::__construct
     * @covers \WindowsAzure\Common\Internal\Http\BatchResponse::getResponses
     */
    public function test__constructWithRequestSuccess(): void
    {
        // Setup
        $statusCode = 200;
        $body = 'test response body';

        $httpCallContext = new HttpCallContext();
        $httpCallContext->addStatusCode($statusCode);

        $batchReq = new BatchRequest();
        $batchReq->appendContext($httpCallContext);

        $encodedBody =
            "--batch_956c339e-1ef0-4443-9276-68c12888a3f7\r\n" .
            "Content-Type: multipart/mixed; boundary=changeset_4a3f1712-c034-416e-9772-905d28c0b122\r\n" .
            "\r\n" .
            "--changeset_4a3f1712-c034-416e-9772-905d28c0b122\r\n" .
            "Content-Transfer-Encoding: binary\r\n" .
            "Content-Type: application/http\r\n" .
            "\r\n" .
            "HTTP/1.1 $statusCode OK\r\n" .
            "content-id: 1\r\n" .
            "\r\n" .
            $body .
            "--changeset_4a3f1712-c034-416e-9772-905d28c0b122--\r\n" .
            '--batch_956c339e-1ef0-4443-9276-68c12888a3f7--';
        $response = new Response(
            200,
            ['Content-Type' => ['boundary=batch_956c339e-1ef0-4443-9276-68c12888a3f7']],
            $encodedBody);

        // Test
        $batchResp = new BatchResponse($response, $batchReq);
        $result = $batchResp->getResponses();

        // Assert
        self::assertCount(1, $result);
        self::assertEquals($body, $result[0]->getBody());
    }

    /**
     * @covers \WindowsAzure\Common\Internal\Http\BatchResponse::__construct
     * @covers \WindowsAzure\Common\Internal\Http\BatchResponse::getResponses
     */
    public function test__constructWithRequestException(): void
    {
        // Setup
        $statusCode = 200;
        $expectedCode = 201;
        $body = 'test response body';

        $httpCallContext = new HttpCallContext();
        $httpCallContext->addStatusCode($expectedCode);

        $batchReq = new BatchRequest();
        $batchReq->appendContext($httpCallContext);

        $encodedBody =
            "--batch_956c339e-1ef0-4443-9276-68c12888a3f7\r\n" .
            "Content-Type: multipart/mixed; boundary=changeset_4a3f1712-c034-416e-9772-905d28c0b122\r\n" .
            "\r\n" .
            "--changeset_4a3f1712-c034-416e-9772-905d28c0b122\r\n" .
            "Content-Transfer-Encoding: binary\r\n" .
            "Content-Type: application/http\r\n" .
            "\r\n" .
            "HTTP/1.1 $statusCode OK\r\n" .
            "content-id: 1\r\n" .
            "\r\n" .
            $body .
            "--changeset_4a3f1712-c034-416e-9772-905d28c0b122--\r\n" .
            '--batch_956c339e-1ef0-4443-9276-68c12888a3f7--';
        $response = new Response(
            200,
            ['content-type' => ['boundary=batch_956c339e-1ef0-4443-9276-68c12888a3f7']],
            $encodedBody);

        $this->expectException(ServiceException::class);

        // Test
        $batchResp = new BatchResponse($response, $batchReq);
        $result = $batchResp->getResponses();
    }
}
