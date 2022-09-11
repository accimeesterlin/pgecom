<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\Address;
use Iyzipay\Model\Consumer;
use Iyzipay\Model\IyziupForm;
use Iyzipay\Model\PaymentResource;

class IyziupFormMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new IyziupFormMapper($rawResult);
    }

    public function mapIyziupFormFrom(IyziupForm $iyziupForm, $jsonObject)
    {
        parent::mapResourceFrom($iyziupForm, $jsonObject);

        if (array_key_exists($jsonObject->orderResponseStatus)) {
            $iyziupForm->setOrderResponseStatus($jsonObject->orderResponseStatus);
        }
        if (array_key_exists($jsonObject->token)) {
            $iyziupForm->setToken($jsonObject->token);
        }
        if (array_key_exists($jsonObject->callbackUrl)) {
            $iyziupForm->setCallbackUrl($jsonObject->callbackUrl);
        }

        if (array_key_exists($jsonObject->consumer)) {
            $iyziupForm->setConsumer(ConsumerMapper::create($jsonObject->consumer)->mapConsumerFrom(new Consumer(), $jsonObject->consumer));
        }

        if (array_key_exists($jsonObject->shippingAddress)) {
            $iyziupForm->setShippingAddress(AddressMapper::create($jsonObject->shippingAddress)->mapAddressFrom(new Address(), $jsonObject->shippingAddress));
        }

        if (array_key_exists($jsonObject->billingAddress)) {
            $iyziupForm->setBillingAddress(AddressMapper::create($jsonObject->billingAddress)->mapAddressFrom(new Address(), $jsonObject->billingAddress));
        }

        if (array_key_exists($jsonObject->paymentDetail)) {
            $iyziupForm->setPaymentDetail(PaymentResourceMapper::create($jsonObject->paymentDetail)->mapPaymentResourceFrom(new PaymentResource(), $jsonObject->paymentDetail));
        }

        return $iyziupForm;
    }

    public function mapIyziupForm(IyziupForm $iyziupForm)
    {
        return $this->mapIyziupFormFrom($iyziupForm, $this->jsonObject);
    }
}