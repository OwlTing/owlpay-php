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

namespace OwlTing\OwlPay\Tests;

use OwlTing\OwlPay\OwlPay;
use PHPUnit\Framework\TestCase;

class FunctionalTestCase extends TestCase
{
    /**
     * The OwlPay API instance.
     *
     * @var \OwlTing\OwlPay\OwlPay
     */
    protected $owlpay;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $this->owlpay = new OwlPay(getenv('OWLPAY_API_KEY'));
    }

    /**
     * @param $currency
     * @param $total
     * @return mixed
     */
    protected function createOrder($currency, $total)
    {
        return $this->owlpay->orders()->create([
            'currency' => $currency,
            'total' => $total,
            'order_serial' => 'ORS00000001_'.time(),
            'description' => 'ORS00000001 on owlpay\'s application',
            'meta_data' => [
                'customer_name' => 'Maras Chen',
                'store_branch_no' => 'SBN_0001',
            ],
            'order_created_at' => date('c', time()),
            'allow_transfer_time_at' => date('c', time()),
        ]);
    }

    /**
     * @param $name
     * @param $email
     * @return mixed
     * @throws \Exception
     */
    protected function createVendor($name, $email)
    {
        return $this->owlpay->vendors()->create([
            'name' => $name,
            'email' => $email,
            'application_vendor_uuid' => time() . random_int(1, 10000),
        ]);
    }
}