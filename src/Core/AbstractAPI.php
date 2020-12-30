<?php

/*
 * This file is part of the nilsir/pacypay.
 *
 * (c) nilsir <nilsir@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Nilsir\Pacypay\Core;

use GuzzleHttp\Middleware;
use Nilsir\Pacypay\Exceptions\HttpException;
use Nilsir\Pacypay\Support\Collection;
use Nilsir\Pacypay\Support\Log;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractAPI
{
    /**
     * Http instance.
     *
     * @var Http
     */
    protected $http;

    protected $container;

    protected $data;

    const GET = 'get';
    const POST = 'post';
    const JSON = 'json';
    const PUT = 'put';
    const DELETE = 'delete';

    /**
     * @var int
     */
    protected static $maxRetries = 2;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Return the http instance.
     *
     * @return Http
     */
    public function getHttp()
    {
        if (is_null($this->http)) {
            $this->http = new Http();
        }

        if (0 === count($this->http->getMiddlewares())) {
            $this->registerHttpMiddlewares();
        }

        return $this->http;
    }

    /**
     * Set the http instance.
     *
     * @return $this
     */
    public function setHttp(Http $http)
    {
        $this->http = $http;

        return $this;
    }

    /**
     * @param int $retries
     */
    public static function maxRetries($retries)
    {
        self::$maxRetries = abs($retries);
    }

    /**
     * Parse JSON from response and check error.
     *
     * @param $method
     *
     * @return Collection|null
     *
     * @throws HttpException
     */
    public function parseJSON($method, array $args)
    {
        $http = $this->getHttp();

        $contents = $http->parseJSON(call_user_func_array([$http, $method], $args));

        if (empty($contents)) {
            return null;
        }

        $this->checkAndThrow($contents);

        return new Collection($contents);
    }

    /**
     * Register Guzzle middlewares.
     */
    protected function registerHttpMiddlewares()
    {
        // log
        $this->http->addMiddleware($this->logMiddleware());
        // retry
        $this->http->addMiddleware($this->retryMiddleware());
    }

    /**
     * Log the request.
     *
     * @return \Closure
     */
    protected function logMiddleware()
    {
        return Middleware::tap(function (RequestInterface $request, $options) {
            Log::debug("Request: {$request->getMethod()} {$request->getUri()} ".json_encode($options));
            Log::debug('Request headers:'.json_encode($request->getHeaders()));
        });
    }

    /**
     * Return retry middleware.
     *
     * @return \Closure
     */
    protected function retryMiddleware()
    {
        return Middleware::retry(function (
            $retries,
            RequestInterface $request,
            ResponseInterface $response = null
        ) {
            // Limit the number of retries to 2
            if ($retries <= self::$maxRetries && $response && $body = $response->getBody()) {
                if (false !== stripos($body, 'ERROR')) {
                    Log::debug("Retry again, error content: {$body}");

                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Check the array data errors, and Throw exception when the contents contains error.
     *
     * @throws HttpException
     */
    protected function checkAndThrow(array $contents)
    {
        if (isset($contents['status']) && 'SUCCESS' !== $contents['status']) {
            if (empty($contents['message'])) {
                $contents['message'] = 'Unknown';
            }

            throw new HttpException($contents['message'], $contents['status']);
        }
    }

    protected function withBaseData(array $data)
    {
        $this->data = array_merge(
            $data,
            [
                'versionNo' => $this->container['config']['version_no'],
                'merchantNo' => $this->container['config']['merchant_no'],
            ]
        );

        return $this;
    }

    protected function withSign()
    {
        $signature = $this->container['signature'];

        return array_merge($this->data, ['sign' => $signature->getSign($this->data)]);
    }
}
