<?php
namespace OwlTing\OwlPay\Tests\Api;

use OwlTing\OwlPay\Exception\NotFoundException;
use OwlTing\OwlPay\Tests\FunctionalTestCase;

class VendorRemitInfoTest extends FunctionalTestCase
{

    /** @test */
    public function it_can_apply_vendor_remit_info_and_retrieve_vendor_remit_info_list()
    {
        $name = 'VendorTest_'.time();

        $email = 'VendorTest_'.time().'_'.random_int(1, 10000).'@owlpay.com';

        $currency = 'TWD';

        $country_iso = "TW";

        $vendor = $this->createVendor($country_iso, $name, $email);

        $vendor = $this->owlpay->vendors()->find($vendor['uuid']);

        $vendor_remit_info = $this->owlpay->vendorRemitInfo()->create($vendor['uuid'], [
            'payout_channel' => 'cathay',
            'applicant' => 'company',
            'aml_data' => [
                // basic information
                'companyName' => 'Maras Test',
                'businessAddressCity' => 'Taipei',
                'businessAddressArea' => 'Taipei',
                'businessAddress' => 'Taipei',
                'companyPhoneCode' => 'TW',
                'companyPhoneNumber' => '0912345678',
                'companyEmail' => 'testing@owlpay.com',
                'companyId' => '12345678',

                // remit_info
                'customName' => 'Maras Account',
                'bankCountry' => 'TW',
                'bankCode' => '013',
                'branchCode' => '0017',
                'currency' => $currency,
                'accountName' => 'OWLPAYMARAS',
                'account' => '264200000000',
            ],
        ]);

        $this->assertSame($currency, $vendor_remit_info[0]['detail']['currency']);

        $vendor_remit_info = $this->owlpay->vendorRemitInfo()->all($vendor['uuid']);

        $this->assertSame($currency, $vendor_remit_info['data'][0]['detail']['currency']);
    }

}