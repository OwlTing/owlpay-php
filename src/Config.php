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

namespace OwlTing\OwlPay;

class Config implements ConfigInterface
{
    /**
     * The current package version.
     *
     * @var string
     */
    protected $version;

    /**
     * The OwlPay API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The OwlPay API version.
     *
     * @var string
     */
    protected $apiVersion;

    /**
     * The idempotency key.
     *
     * @var string|null
     */
    protected $idempotencyKey;

    /**
     * The application's information.
     *
     * @var array|null
     */
    protected $appInfo;

    /**
     * Constructor.
     *
     * @param  string  $version
     * @param  string  $apiKey
     * @param  string  $apiVersion
     * @return void
     */
    public function __construct($version, $apiKey, $apiVersion)
    {
        $this->setVersion($version);

        $this->setApiKey($apiKey ?: self::getEnvVariable('OWLPAY_API_KEY', ''));

        $this->setApiVersion($apiVersion ?: self::getEnvVariable('OWLPAY_API_VERSION', 'v1'));
    }

    /**
     * @param string      $name
     * @param string|null $default
     *
     * @return string|null
     */
    private static function getEnvVariable($name, $default = null)
    {
        if (isset($_SERVER[$name])) {
            return (string) $_SERVER[$name];
        }

        if (PHP_SAPI === 'cli' && ($value = getenv($name)) !== false) {
            return (string) $value;
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdempotencyKey()
    {
        return $this->idempotencyKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdempotencyKey($idempotencyKey)
    {
        $this->idempotencyKey = $idempotencyKey;

        return $this;
    }
    /**
     * Returns the application's information.
     *
     * @return array|null
     */
    public function getAppInfo()
    {
        return $this->appInfo;
    }

    /**
     * Sets the application's information.
     *
     * @param string $appName
     * @param string $appVersion
     * @param string $appUrl
     * @return $this
     */
    public function setAppInfo($appName, $appVersion = null, $appUrl = null)
    {
        $this->appInfo = [
            'name'       => $appName,
            'version'    => $appVersion,
            'url'        => $appUrl,
        ];

        return $this;
    }
}
