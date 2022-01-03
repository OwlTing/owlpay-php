<?php
namespace OwlTing\OwlPay\Tests\Api;

use OwlTing\OwlPay\Exception\NotFoundException;
use OwlTing\OwlPay\Tests\FunctionalTestCase;

class OrdersTest extends FunctionalTestCase
{
    /** @test */
    public function it_can_create_a_new_order()
    {
        $currency = 'TWD';

        $total = 1000;

        $order = $this->createOrder($currency, $total);

        $this->assertSame('owlpay.received_order', $order['status']);
    }

    /** @test */
    public function it_can_find_an_order()
    {
        $currency = 'TWD';

        $total = 1000;

        $order = $this->createOrder($currency, $total);

        $order = $this->owlpay->orders()->find($order['uuid']);

        $this->assertSame('owlpay.received_order', $order['status']);
    }

    /** @test */
    public function it_will_throw_an_exception_when_searching_for_a_non_existing_order()
    {
        $this->expectException(NotFoundException::class);

        $this->owlpay->orders()->find(time().rand());
    }

    /** @test */
    public function it_can_update_an_order()
    {
        $currency = 'TWD';

        $total = 1000;

        $order = $this->createOrder($currency, $total);

        $order = $this->owlpay->orders()->update($order['uuid'], [
            'meta_data' => [ 'foo' => 'Bar' ],
        ]);

        $this->assertSame([ 'foo' => 'Bar' ], $order['meta_data']);
    }

    /** @test */
    public function it_can_cancel_an_order()
    {
        $currency = 'TWD';

        $total = 1000;

        $order = $this->createOrder($currency, $total);

        $order = $this->owlpay->orders()->cancel([$order['uuid']]);

        $this->assertSame('platform.order_cancelled', $order[0]['status']);
    }

    /** @test */
    public function it_can_retrieve_all_orders()
    {
        $orders = $this->owlpay->orders()->all();

        $this->assertNotEmpty($orders['data']);
        $this->assertIsArray($orders['data']);
    }
}