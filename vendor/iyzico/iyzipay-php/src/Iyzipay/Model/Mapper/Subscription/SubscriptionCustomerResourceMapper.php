<?php

namespace Iyzipay\Model\Mapper\Subscription;

use Iyzipay\Model\Subscription\SubscriptionCustomer;
use Iyzipay\Model\Mapper\IyzipayResourceMapper;

class SubscriptionCustomerResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new SubscriptionCustomerResourceMapper($rawResult);
    }

    public function mapSubscriptionCustomerResourceFrom(SubscriptionCustomer $create, $jsonObject)
    {
        parent::mapResourceFrom($create, $jsonObject);

        if (array_key_exists($jsonObject->data->referenceCode)) {
            $create->setReferenceCode($jsonObject->data->referenceCode);
        }
        if (array_key_exists($jsonObject->data->status)) {
            $create->setCustomerStatus($jsonObject->data->status);
        }
        if (array_key_exists($jsonObject->data->name)) {
            $create->setName($jsonObject->data->name);
        }
        if (array_key_exists($jsonObject->data->surname)) {
            $create->setSurname($jsonObject->data->surname);
        }
        if (array_key_exists($jsonObject->data->identityNumber)) {
            $create->setIdentityNumber($jsonObject->data->identityNumber);
        }
        if (array_key_exists($jsonObject->data->email)) {
            $create->setEmail($jsonObject->data->email);
        }
        if (array_key_exists($jsonObject->data->gsmNumber)) {
            $create->setGsmNumber($jsonObject->data->gsmNumber);
        }
        if (array_key_exists($jsonObject->data->contactEmail)) {
            $create->setContactEmail($jsonObject->data->contactEmail);
        }
        if (array_key_exists($jsonObject->data->contactGsmNumber)) {
            $create->setContactGsmNumber($jsonObject->data->contactGsmNumber);
        }
        if (array_key_exists($jsonObject->data->billingAddress->contactName)) {
            $create->setBillingContactName($jsonObject->data->billingAddress->contactName);
        }
        if (array_key_exists($jsonObject->data->billingAddress->city)) {
            $create->setBillingCity($jsonObject->data->billingAddress->city);
        }
        if (array_key_exists($jsonObject->data->billingAddress->district)) {
            $create->setBillingDistrict($jsonObject->data->billingAddress->district);
        }
        if (array_key_exists($jsonObject->data->billingAddress->country)) {
            $create->setBillingCountry($jsonObject->data->billingAddress->country);
        }
        if (array_key_exists($jsonObject->data->billingAddress->address)) {
            $create->setBillingAddress($jsonObject->data->billingAddress->address);
        }
        if (array_key_exists($jsonObject->data->billingAddress->zipCode)) {
            $create->setBillingZipCode($jsonObject->data->billingAddress->zipCode);
        }

        if (array_key_exists($jsonObject->data->shippingAddress->contactName)) {
            $create->setShippingContactName($jsonObject->data->shippingAddress->contactName);
        }
        if (array_key_exists($jsonObject->data->shippingAddress->city)) {
            $create->setShippingCity($jsonObject->data->shippingAddress->city);
        }
        if (array_key_exists($jsonObject->data->shippingAddress->district)) {
            $create->setShippingDistrict($jsonObject->data->shippingAddress->district);
        }
        if (array_key_exists($jsonObject->data->shippingAddress->country)) {
            $create->setShippingCountry($jsonObject->data->shippingAddress->country);
        }
        if (array_key_exists($jsonObject->data->shippingAddress->address)) {
            $create->setShippingAddress($jsonObject->data->shippingAddress->address);
        }
        if (array_key_exists($jsonObject->data->shippingAddress->zipCode)) {
            $create->setShippingZipCode($jsonObject->data->shippingAddress->zipCode);
        }

        if (array_key_exists($jsonObject->data->createdDate)) {
            $create->setCreatedDate($jsonObject->data->createdDate);
        }
        return $create;
    }

    public function mapSubscriptionCustomer(SubscriptionCustomer $subscriptionCustomer)
    {
        return $this->mapSubscriptionCustomerResourceFrom($subscriptionCustomer, $this->jsonObject);
    }

}