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

interface ConfigInterface
{
    /**
     * Returns the current package version.
     *
     * @return string
     */
    public function getVersion();

    /**
     * Sets the current package version.
     *
     * @param  string  $version
     * @return $this
     */
    public function setVersion($version);

    /**
     * Returns the Stripe API key.
     *
     * @return string
     */
    public function getApiKey();

    /**
     * Sets the Stripe API key.
     *
     * @param  string  $apiKey
     * @return $this
     */
    public function setApiKey($apiKey);

    /**
     * Returns the Stripe API version.
     *
     * @return string
     */
    public function getApiVersion();

    /**
     * Sets the Stripe API version.
     *
     * @param  string  $apiVersion
     * @return $this
     */
    public function setApiVersion($apiVersion);

    /**
     * Returns the idempotency key.
     *
     * @return string|null
     */
    public function getIdempotencyKey();

    /**
     * Sets the idempotency key.
     *
     * @param  string|null  $idempotencyKey
     * @return $this
     */
    public function setIdempotencyKey($idempotencyKey);
}
