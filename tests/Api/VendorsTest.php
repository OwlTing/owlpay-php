<?php
namespace OwlTing\OwlPay\Tests\Api;

use OwlTing\OwlPay\Exception\NotFoundException;
use OwlTing\OwlPay\Tests\FunctionalTestCase;

class VendorsTest extends FunctionalTestCase
{
    /** @test */
    public function it_can_create_a_new_vendor()
    {
        $name = 'VendorTest_'.time();

        $email = 'VendorTest_'.time().'_'.random_int(1, 10000).'@owlpay.com';

        $vendor = $this->owlpay->vendors()->create(compact('name', 'email'));

        $this->assertSame($email, $vendor['email']);
    }

    /** @test */
    public function it_can_find_a_vendor()
    {
        $name = 'VendorTest_'.time();

        $email = 'VendorTest_'.time().'_'.random_int(1, 10000).'@owlpay.com';

        $vendor = $this->createVendor($name, $email);

        $vendor = $this->owlpay->vendors()->find($vendor['uuid']);

        $this->assertSame($email, $vendor['email']);
    }

    /** @test */
    public function it_will_throw_an_exception_when_searching_for_a_non_existing_vendor()
    {
        $this->expectException(NotFoundException::class);

        $this->owlpay->vendors()->find(time().rand());
    }

    /** @test */
    public function it_can_update_a_vendor()
    {
        $name = 'VendorTest_'.time();

        $email = 'VendorTest_'.time().'_'.random_int(1, 10000).'@owlpay.com';

        $updated_email = 'Updated_VendorTest_'.time().'_'.random_int(1, 10000).'@owlpay.com';

        $vendor = $this->createVendor($name, $email);

        $updated_vendor = $this->owlpay->vendors()->update($vendor['uuid'],
            ['email' => $updated_email],
        );

        $this->assertSame($updated_email, $updated_vendor['email']);
    }

    /** @test */
    public function it_can_delete_a_vendor()
    {
        $name = 'VendorTest_'.time();

        $email = 'VendorTest_'.time().'_'.random_int(1, 10000).'@owlpay.com';

        $vendor = $this->createVendor($name, $email);

        $vendor = $this->owlpay->vendors()->delete($vendor['uuid']);

        $this->assertTrue($vendor['deleted']);
    }

    /** @test */
    public function it_can_retrieve_vendors()
    {
        $vendors = $this->owlpay->vendors()->all();

        $this->assertNotEmpty($vendors['data']);
        $this->assertIsArray($vendors['data']);
    }
}