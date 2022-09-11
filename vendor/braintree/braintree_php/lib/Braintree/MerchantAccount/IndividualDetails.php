<?php
namespace Braintree\MerchantAccount;

use Braintree\Base;

class IndividualDetails extends Base
{
    protected function _initialize($individualAttribs)
    {
        $this->_attributes = $individualAttribs;
        if (array_key_exists($individualAttribs['address'])) {
            $this->_set('addressDetails', new AddressDetails($individualAttribs['address']));
        }
    }

    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }
}
