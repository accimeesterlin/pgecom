<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\Address;

class AddressMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new AddressMapper($rawResult);
    }

    public function mapAddressFrom(Address $address, $jsonObject)
    {
        if (array_key_exists($jsonObject->address)) {
            $address->setAddress($jsonObject->address);
        }
        if (array_key_exists($jsonObject->zipCode)) {
            $address->setZipCode($jsonObject->zipCode);
        }
        if (array_key_exists($jsonObject->contactName)) {
            $address->setContactName($jsonObject->contactName);
        }
        if (array_key_exists($jsonObject->city)) {
            $address->setCity($jsonObject->city);
        }
        if (array_key_exists($jsonObject->country)) {
            $address->setCountry($jsonObject->country);
        }
        return $address;
    }
}