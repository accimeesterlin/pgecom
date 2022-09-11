<?php

namespace Omnipay\Braintree\Message;

/**
 * CustomerResponse
 */
class CustomerResponse extends Response
{
    public function getCustomerData()
    {
        if (array_key_exists($this->data->customer)) {
            return $this->data->customer;
        }

        return null;
    }
}
