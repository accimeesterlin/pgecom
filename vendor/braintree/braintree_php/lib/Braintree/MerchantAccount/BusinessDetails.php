<?php
namespace Braintree\MerchantAccount;

use Braintree\Base;

class BusinessDetails extends Base
{
    protected function _initialize($businessAttribs)
    {
        $this->_attributes = $businessAttribs;
        if (array_key_exists($businessAttribs['address'])) {
            $this->_set('addressDetails', new AddressDetails($businessAttribs['address']));
        }
    }

    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }
}
