<?php

namespace OwlTing\OwlPay\Api;

class VendorRemitInfo extends Api
{
    /**
     * Creates a new vendor.
     *
     * @param  array  $parameters
     * @return array
     */
    public function create($vendor_uuid, array $parameters = [])
    {
        return $this->_post("vendors/$vendor_uuid/remit_info/apply", $parameters)['data'];
    }

    /**
     * Returns a list of all the vendors.
     *
     * @param  array  $parameters
     * @return array
     */
    public function all($vendor_uuid, array $parameters = [])
    {
        return $this->_get("vendors/$vendor_uuid/remit_info", $parameters);
    }
}
