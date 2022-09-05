<?php

/**
 * Part of the OwlPay package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    OwlPay
 * @version    1.0.1
 * @author     OwlTing Group
 * @license    BSD License (3-clause)
 * @copyright  (c) 2021-2022, OwlTing Group
 * @link       https://owlpay.com
 */

namespace OwlTing\OwlPay\Api;

use OwlTing\OwlPay\Utility;
use RuntimeException;
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use OwlTing\OwlPay\ConfigInterface;
use Psr\Http\Message\RequestInterface;
use OwlTing\OwlPay\Exception\Handler;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;

abstract class Api implements ApiInterface
{
    /**
     * The Config repository instance.
     *
     * @var \OwlTing\OwlPay\ConfigInterface
     */
    protected $config;

    /**
     * Number of items to return per page.
     *
     * @var null|int
     */
    protected $perPage;

    /**
     * The idempotency key.
     *
     * @var string
     */
    protected $idempotencyKey;

    /**
     * Constructor.
     *
     * @param  \OwlTing\OwlPay\ConfigInterface  $client
     * @return void
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function baseUrl()
    {
        return getenv('OWLPAY_BASE_URL', 'https://api.owlpay.com/');
    }

    /**
     * {@inheritdoc}
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * {@inheritdoc}
     */
    public function setPerPage($perPage)
    {
        $this->perPage = (int) $perPage;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function idempotent($idempotencyKey)
    {
        $this->idempotencyKey = $idempotencyKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function _get($url = null, $parameters = [])
    {
        if ($perPage = $this->getPerPage()) {
            $parameters['limit'] = $perPage;
        }

        return $this->execute('get', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _head($url = null, array $parameters = [])
    {
        return $this->execute('head', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _delete($url = null, array $parameters = [])
    {
        return $this->execute('delete', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _put($url = null, array $parameters = [])
    {
        return $this->execute('put', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _patch($url = null, array $parameters = [])
    {
        return $this->execute('patch', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _post($url = null, array $parameters = [])
    {
        if (isset(array_flip(array_keys($parameters))['query'])) {
            $parameters['form_data'] = $parameters['query'];
            unset($parameters['query']);
        }

        return $this->execute('post', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _options($url = null, array $parameters = [])
    {
        return $this->execute('options', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($httpMethod, $url, array $parameters = [])
    {
        try {
            $parameters = Utility::prepareParameters($parameters);

            $response = $this->getClient()->{$httpMethod}('api/v1/platform/tunnel/'.$url, [ 'query' => $parameters ]);

            return json_decode((string) $response->getBody(), true);
        } catch (ClientException $e) {
            new Handler($e);
        }
    }

    /**
     * Returns an Http client instance.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getClient()
    {
        return new Client([
            'base_uri' => $this->baseUrl(), 'handler' => $this->createHandler()
        ]);
    }

    /**
     * Create the client handler.
     *
     * @return \GuzzleHttp\HandlerStack
     * @throws \RuntimeException
     */
    protected function createHandler()
    {
        if (! $this->config->getApiKey()) {
            throw new RuntimeException('The OwlPay API key is not defined!');
        }

        $stack = HandlerStack::create();

        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            $config = $this->config;

            $idempotencykey = $this->idempotencyKey ?: $config->getIdempotencyKey();

            if ($idempotencykey) {
                $request = $request->withHeader('Idempotency-Key', $idempotencykey);
            }

            $request = $request->withHeader('User-Agent', $this->generateUserAgent());

            $request = $request->withHeader('OwlPay-Version', $config->getApiVersion());

            $request = $request->withHeader('Authorization', 'Bearer '.$config->getApiKey());

            $request = $request->withHeader('X-OwlPay-Client-User-Agent', $this->generateClientUserAgentHeader());

            return $request;
        }));

        $stack->push(Middleware::retry(function ($retries, RequestInterface $request, ResponseInterface $response = null, TransferException $exception = null) {
            return $retries < 3 && ($exception instanceof ConnectException || ($response && $response->getStatusCode() >= 500));
        }, function ($retries) {
            return (int) pow(2, $retries) * 1000;
        }));

        return $stack;
    }

    /**
     * Generates the main user agent string.
     *
     * @return string
     */
    protected function generateUserAgent()
    {
        $appInfo = $this->config->getAppInfo();

        $userAgent = 'OwlTing-OwlPay/'.$this->config->getVersion();

        if ($appInfo || ! empty($appInfo)) {
            $userAgent .= ' '.$appInfo['name'];

            if ($appVersion = $appInfo['version']) {
                $userAgent .= "/{$appVersion}";
            }

            if ($appUrl = $appInfo['url']) {
                $userAgent .= " ({$appUrl})";
            }
        }

        return $userAgent;
    }

    /**
     * Generates the client user agent header value.
     *
     * @return string
     */
    protected function generateClientUserAgentHeader()
    {
        $appInfo = $this->config->getAppInfo();

        $userAgent = [
            'bindings_version' => $this->config->getVersion(),
            'lang'             => 'php',
            'lang_version'     => phpversion(),
            'publisher'        => 'owlting',
            'uname'            => php_uname(),
        ];

        if ($appInfo || ! empty($appInfo)) {
            $userAgent['application'] = $appInfo;
        }

        return json_encode($userAgent);
    }
}
