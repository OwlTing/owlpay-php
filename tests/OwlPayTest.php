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

use BadMethodCallException;
use Mockery as mockery;
use OwlTing\OwlPay\OwlPay;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class OwlPayTest extends TestCase
{
    /**
     * The OwlPay API client instance.
     *
     * @var \OwlTing\OwlPay\OwlPay
     */
    protected $owlpay;

    /**
     * Setup resources and dependencies
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->owlpay = new OwlPay('owlpay-api-key', 'v1');
    }

    /**
     * Close mockery.
     *
     * @return void
     */
    public function tearDown(): void
    {
        mockery::close();
    }

    /** @test */
    public function it_can_create_a_new_instance_using_the_make_method()
    {
        $owlpay = OwlPay::make('owlpay-api-key');

        $this->assertEquals('owlpay-api-key', $owlpay->getApiKey());
    }

    /** @test */
    public function it_can_create_a_new_instance_using_enviroment_variables()
    {
        $owlpay = new OwlPay;

        $this->assertEquals(getenv('OWLPAY_API_KEY'), $owlpay->getApiKey());

        $this->assertEquals(getenv('OWLPAY_API_VERSION'), $owlpay->getApiVersion());
    }

    /** @test */
    public function it_can_get_and_set_the_api_key()
    {
        $this->owlpay->setApiKey('new-owlpay-api-key');

        $this->assertEquals('new-owlpay-api-key', $this->owlpay->getApiKey());
    }

    /** @test */
    public function it_can_get_and_set_the_api_version()
    {
        $this->owlpay->setApiVersion('v1');

        $this->assertEquals('v1', $this->owlpay->getApiVersion());
    }

    /** @test */
    public function it_can_get_the_current_package_version()
    {
        $version = $this->owlpay->getVersion();

        $this->assertSame('1.0.1', $version);
    }

    /** @test */
    public function it_can_create_requests()
    {
        $class = $this->owlpay->orders();

        $this->assertInstanceOf('OwlTing\\OwlPay\\Api\\Orders', $class);
    }

    /** @test */
    public function it_throws_an_exception_when_the_request_is_invalid()
    {
        $this->expectException(BadMethodCallException::class);

        $this->owlpay->foo();
    }

    /** @test */
    public function can_retrieve_the_owlpay_headers_from_thrown_exception()
    {
        try {
            $owlpay = new OwlPay();

            $owlpay->orders()->find('non-existent-order-'.time());
        } catch (\Exception $e) {
            $headers = $e->getHeaders();

            $this->assertNotNull($headers['X-RateLimit-Limit']);
        }
    }

    /** @test */
    public function it_throws_an_exception_when_the_api_key_is_not_set()
    {
        $this->expectException(RuntimeException::class);

        unset($_SERVER['OWLPAY_API_KEY']);
        putenv('OWLPAY_API_KEY');

        $owlpay = new OwlPay();

        $owlpay->orders()->all();
    }
}
