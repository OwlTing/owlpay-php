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

use OwlTing\OwlPay\Utility;
use PHPUnit\Framework\TestCase;

class UtilityTest extends TestCase
{
    /** @test */
    public function it_can_prepare_the_parameters_for_the_request()
    {
        $this->assertSame('bool=true', Utility::prepareParameters([ 'bool' => true ]));
        $this->assertSame('bool=true', Utility::prepareParameters([ 'bool' => 'true' ]));

        $this->assertSame('bool=false', Utility::prepareParameters([ 'bool' => false ]));
        $this->assertSame('bool=false', Utility::prepareParameters([ 'bool' => 'false' ]));
    }
}