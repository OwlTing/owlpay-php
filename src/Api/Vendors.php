<?php

namespace OwlTing\OwlPay\Api;

class Vendors extends Api
{
    /**
     * Creates a new vendor.
     *
     * @param  array  $parameters
     * @return array
     */
    public function create(array $parameters = [])
    {
        return $this->_post('vendors', $parameters)['data'];
    }

    /**
     * Retrieves an existing vendor.
     *
     * @param  string  $vendorUUId
     * @return array
     */
    public function find($vendorUUId)
    {
        return $this->_get("vendors/{$vendorUUId}")['data'];
    }

    /**
     * Updates an existing vendor.
     *
     * @param  string  $vendorUUId
     * @param  array  $parameters
     * @return array
     */
    public function update($vendorUUId, array $parameters = [])
    {
        return $this->_put("vendors/{$vendorUUId}", $parameters)['data'];
    }

    /**
     * Cancel an existing vendor.
     *
     * @param  string  $vendorUUId
     * @return array
     */
    public function delete($vendorUUId)
    {
        return $this->_delete("vendors/{$vendorUUId}")['data'];
    }

    /**
     * Returns a list of all the vendors.
     *
     * @param  array  $parameters
     * @return array
     */
    public function all(array $parameters = [])
    {
        return $this->_get('vendors', $parameters);
    }
}
