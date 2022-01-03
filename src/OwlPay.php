<?php

namespace OwlTing\OwlPay;

use ReflectionClass;

class OwlPay
{
    /**
     * The package version.
     *
     * @var string
     */
    const VERSION = '1.0.1';

    /**
     * The Config repository instance.
     *
     * @var \OwlTing\OwlPay\ConfigInterface
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param  string  $apiKey
     * @param  string  $apiVersion
     * @return void
     */
    public function __construct($apiKey = null, $apiVersion = null)
    {
        $this->config = new Config(self::VERSION, $apiKey, $apiVersion);
    }

    /**
     * Create a new OwlPay API instance.
     *
     * @param  string  $apiKey
     * @param  string  $apiVersion
     * @return \OwlTing\OwlPay\OwlPay
     */
    public static function make($apiKey = null, $apiVersion = null)
    {
        return new static($apiKey, $apiVersion);
    }

    /**
     * Returns the current package version.
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Returns the Config repository instance.
     *
     * @return \OwlTing\OwlPay\ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets the Config repository instance.
     *
     * @param  \OwlTing\OwlPay\ConfigInterface  $config
     * @return $this
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Returns the Stripe API key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->config->getApiKey();
    }

    /**
     * Sets the Stripe API key.
     *
     * @param  string  $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->config->setApiKey($apiKey);

        return $this;
    }

    /**
     * Returns the Stripe API version.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->config->getApiVersion();
    }

    /**
     * Sets the Stripe API version.
     *
     * @param  string  $apiVersion
     * @return $this
     */
    public function setApiVersion($apiVersion)
    {
        $this->config->setApiVersion($apiVersion);

        return $this;
    }

    /**
     * Dynamically handle missing methods.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \OwlTing\OwlPay\Api\ApiInterface
     */
    public function __call($method, array $parameters)
    {
        return $this->getApiInstance($method);
    }

    /**
     * Returns the Api class instance for the given method.
     *
     * @param  string  $method
     * @return \OwlTing\OwlPay\Api\ApiInterface
     * @throws \BadMethodCallException
     */
    protected function getApiInstance($method)
    {
        $class = "\\OwlTing\\OwlPay\\Api\\".ucwords($method);

        if (class_exists($class) && ! (new ReflectionClass($class))->isAbstract()) {
            return new $class($this->config);
        }

        throw new \BadMethodCallException("Undefined method [{$method}] called.");
    }
}