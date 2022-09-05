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

class Utility
{
    /**
     * Prepares the given parameters.
     *
     * @param  array  $parameters
     * @return string
     */
    public static function prepareParameters(array $parameters)
    {
        $parameters = array_map(function ($parameter) {
            return is_bool($parameter) ? ($parameter === true ? 'true' : 'false') : $parameter;
        }, $parameters);

        return http_build_query($parameters);
    }
}
