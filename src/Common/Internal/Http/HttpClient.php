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

namespace WindowsAzure\Common\Internal\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use WindowsAzure\Common\Internal\Exception\NoResponseException;
use WindowsAzure\Common\Internal\Exception\NoUrlException;
use WindowsAzure\Common\Internal\IServiceFilter;
use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\Internal\Validate;
use WindowsAzure\Common\ServiceException;

/**
 * HTTP client which sends and receives HTTP requests and responses.
 *
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>, Basel Ahmed <baselsoftwaredev@gmail.com>
 * @copyright 2012 Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/baselsoftwaredev/azure-service-bus
 * @version   0.1.0
 * @category  Microsoft
 */
class HttpClient implements IHttpClient
{
    /**
     * @var string
     */
    private string $_method = Resources::HTTP_GET;

    /**
     * @var array<string, bool|string>
     */
    private array $_requestOptions = [
        RequestOptions::ALLOW_REDIRECTS => false,
    ];

    /**
     * @var array<string, string>
     */
    private array $_postParams = [];

    /**
     * @var array<string, string>
     */
    private array $_headers = [];

    /**
     * @var string
     */
    private string $_body = '';

    /**
     * @var ?IUrl
     */
    private ?IUrl $_requestUrl;

    /**
     * @var array<string, bool|string>
     */
    private array $_config;

    /**
     * Holds expected status code after sending the request.
     *
     * @var array<int, int>
     */
    private array $_expectedStatusCodes;

    /**
     * Initializes new HttpClient object.
     *
     * @param string $certificatePath          The certificate path
     * @param string $certificateAuthorityPath The path of the certificate authority
     */
    public function __construct(
        string $certificatePath = Resources::EMPTY_STRING,
        string $certificateAuthorityPath = Resources::EMPTY_STRING
    )
    {
        $this->_config = [
            Resources::USE_BRACKETS => true,
            Resources::SSL_VERIFY_PEER => false,
            Resources::SSL_VERIFY_HOST => false,
        ];

        if ($certificatePath !== '') {
            $this->_config[Resources::SSL_LOCAL_CERT] = $certificatePath;
            $this->_config[Resources::SSL_VERIFY_HOST] = true;
            $this->_requestOptions[RequestOptions::CERT] = $certificatePath;
        }

        if ($certificateAuthorityPath !== '') {
            $this->_config[Resources::SSL_CAFILE] = $certificateAuthorityPath;
            $this->_config[Resources::SSL_VERIFY_PEER] = true;
        }

        // Replace User-Agent.
        $this->setHeader(Resources::USER_AGENT, Resources::SDK_USER_AGENT, true);

        $this->_requestUrl = null;
        $this->_expectedStatusCodes = [];
    }

    /**
     * Sets an existing request header to value or creates a new one if the $header
     * doesn't exist.
     *
     * @param string $header  header name
     * @param string $value   header value
     * @param bool   $replace whether to replace previous header with the same name
     *                        or append to its value (comma separated)
     */
    public function setHeader(string $header, string $value, bool $replace = false): void
    {
        // Header names are case-insensitive
        $header = strtolower($header);
        if (! isset($this->_headers[$header]) || $replace) {
            $this->_headers[$header] = $value;
        } else {
            $this->_headers[$header] .= ', ' . $value;
        }
    }

    /**
     * @param ResponseInterface $response
     * @return string[]
     */
    public static function getResponseHeaders(ResponseInterface $response): array
    {
        $responseHeaderArray = $response->getHeaders();

        /** @var string[] $responseHeaders */
        $responseHeaders = [];

        foreach ($responseHeaderArray as $key => $value) {
            $responseHeaders[strtolower($key)] = implode(',', $value);
        }

        return $responseHeaders;
    }

    /**
     * Makes deep copy from the current object.
     */
    public function __clone()
    {
        if (! is_null($this->_requestUrl)) {
            $this->_requestUrl = clone $this->_requestUrl;
        }
    }

    /**
     * Gets request's HTTP method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->_method;
    }

    /**
     * Sets request's HTTP method. You can use constants like
     * Resources::HTTP_GET or strings like 'GET'.
     *
     * @param string $method request's HTTP method
     */
    public function setMethod(string $method): void
    {
        $this->_method = strtoupper($method);
    }

    /**
     * Gets request's headers. The returned array key (header names) are all in
     * lower case even if they were set having some upper letters.
     *
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->_headers;
    }

    /**
     * Sets request headers using array.
     *
     * @param array<string, string> $headers headers key-value array
     */
    public function setHeaders(array $headers): void
    {
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value);
        }
    }

    /**
     * Sets HTTP POST parameters.
     *
     * @param array<string, string> $postParameters The HTTP POST parameters
     */
    public function setPostParameters(array $postParameters): void
    {
        foreach ($postParameters as $k => $v) {
            $this->_postParams[$k] = $v;
        }
    }

    /**
     * Processes the request through HTTP pipeline with passed $filters,
     * sends HTTP requests to the wire and process the response in the HTTP pipeline.
     *
     * @param array<int, IServiceFilter> $filters HTTP filters which will be applied to the request before
     *                                            send and then applied to the response
     * @param IUrl|null                  $url     Request url
     * @return string The response body
     * @throws GuzzleException
     */
    public function send(array $filters, IUrl $url = null): string
    {
        return (string) ($this->sendAndGetHttpResponse($filters, $url)->getBody());
    }

    /**
     * Processes the request through HTTP pipeline with passed $filters,
     * sends HTTP requests to the wire and process the response in the HTTP pipeline.
     *
     * @param array<int, IServiceFilter> $filters HTTP filters which will be applied to the request before
     *                                            send and then applied to the response
     * @param IUrl|null                  $url     Request url
     * @return ResponseInterface The response
     * @throws GuzzleException
     * @throws Exception
     */
    public function sendAndGetHttpResponse(array $filters, IUrl $url = null): ResponseInterface
    {
        if ($url !== null) {
            $this->setUrl($url);
        }

        if ($this->getUrl() === null) {
            throw new NoUrlException;
        }

        foreach ($filters as $filter) {
            $filter->handleRequest($this);
        }

        $client = new Client($this->_config);

        // send request and receive a response
        try {
            $options = $this->_requestOptions;

            if (count($this->_postParams) === 0) {
                $options[RequestOptions::FORM_PARAMS] = $this->_postParams;
            }

            // Since PHP 5.6, a default value for certificate validation is 'true'.
            // We set it back to false if an environment variable 'HTTPS_PROXY' is
            // defined.
            $httpsProxy = getenv('HTTPS_PROXY');
            if (is_string($httpsProxy) && $httpsProxy !== '') {
                $options[RequestOptions::VERIFY] = false;
            }

            $request = new Request(
                $this->_method,
                $this->getUrl()->getUrl(),
                $this->_headers,
                $this->_body);

            $response = $client->send($request, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        if ($response === null) {
            throw new NoResponseException;
        }

        $start = count($filters) - 1;
        for ($index = $start; $index >= 0; --$index) {

            $response = $filters[$index]->handleResponse($this, $response);
        }

        self::throwIfError(
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            $response->getBody(),
            $this->_expectedStatusCodes
        );

        return $response;
    }

    /**
     * Sets the request url.
     *
     * @param IUrl $url request url
     */
    public function setUrl(IUrl $url): void
    {
        $this->_requestUrl = $url;
    }

    /**
     * Gets request url. Note that you must check if the returned object is null or
     * not.
     *
     * @return ?IUrl
     */
    public function getUrl(): ?IUrl
    {
        return $this->_requestUrl;
    }

    /**
     * Throws ServiceException if the received status code is not expected.
     *
     * @param int             $actual   The received status code
     * @param string          $reason   The reason phrase
     * @param string          $message  The detailed message (if any)
     * @param array<int, int> $expected The expected status codes
     * @throws ServiceException
     */
    public static function throwIfError(int $actual, string $reason, string $message, array $expected): void
    {
        if (! in_array($actual, $expected, true)) {
            throw new ServiceException($actual, $reason, $message);
        }
    }

    /**
     * Sets successful status code.
     *
     * @param array<int, mixed> $statusCodes successful status code
     */
    public function setExpectedStatusCode(array $statusCodes): void
    {
        foreach ($statusCodes as $statusCode) {
            Validate::isInteger($statusCode, 'statusCode');
            $this->_expectedStatusCodes[] = ! is_int($statusCode) ? (int) $statusCode : $statusCode;
        }
    }

    /**
     * Gets successful status code.
     *
     * @return array<int, int>
     */
    public function getSuccessfulStatusCode(): array
    {
        return $this->_expectedStatusCodes;
    }

    /**
     * Gets value for configuration parameter.
     *
     * @param string $name configuration parameter name
     * @return bool|string
     */
    public function getConfig(string $name)
    {
        return $this->_config[$name];
    }

    /**
     * Sets configuration parameter.
     *
     * @param string $name  The configuration parameter name
     * @param mixed  $value The configuration parameter value
     */
    public function setConfig(string $name, $value = null): void
    {
        $this->_config[$name] = $value;
    }

    /**
     * Gets the request body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->_body;
    }

    /**
     * Sets the request body.
     *
     * @param string $body body to use
     */
    public function setBody(string $body): void
    {
        $this->_body = $body;
    }
}
