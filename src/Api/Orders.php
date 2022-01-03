<?php

namespace OwlTing\OwlPay\Api;

class Orders extends Api
{
    /**
     * Creates a new order.
     *
     * @param  array  $parameters
     * @return array
     */
    public function create(array $parameters = [])
    {
        return $this->_post('orders', $parameters)['data'];
    }

    /**
     * Retrieves an existing order.
     *
     * @param  string  $orderUUId
     * @return array
     */
    public function find($orderUUId)
    {
        return $this->_get("orders/{$orderUUId}")['data'];
    }

    /**
     * Updates an existing order.
     *
     * @param  string  $orderUUId
     * @param  array  $parameters
     * @return array
     */
    public function update($orderUUId, array $parameters = [])
    {
        return $this->_put("orders/{$orderUUId}", $parameters)['data'];
    }

    /**
     * Updates an existing order.
     *
     * @param  string  $orderUUId
     * @return array
     */
    public function cancel($orderUUIds)
    {
        if (is_array($orderUUIds)) {
            $parameters['order_uuids'] = $orderUUIds;
        } else {
            $parameters['order_uuids'][] = $orderUUIds;
        }

        return $this->_put("orders/cancel", $parameters)['data'];
    }

    /**
     * Returns a list of all the orders.
     *
     * @param  array  $parameters
     * @return array
     */
    public function all(array $parameters = [])
    {
        return $this->_get('orders', $parameters);
    }
}