<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\CheckoutFormInitializeResource;

class CheckoutFormInitializeResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new CheckoutFormInitializeMapper($rawResult);
    }

    public function mapCheckoutFormInitializeResourceFrom(CheckoutFormInitializeResource $initialize, $jsonObject)
    {
        parent::mapResourceFrom($initialize, $jsonObject);

        if (array_key_exists($jsonObject->token)) {
            $initialize->setToken($jsonObject->token);
        }
        if (array_key_exists($jsonObject->checkoutFormContent)) {
            $initialize->setCheckoutFormContent($jsonObject->checkoutFormContent);
        }
        if (array_key_exists($jsonObject->tokenExpireTime)) {
            $initialize->setTokenExpireTime($jsonObject->tokenExpireTime);
        }
        if (array_key_exists($jsonObject->paymentPageUrl)) {
            $initialize->setPaymentPageUrl($jsonObject->paymentPageUrl);
        }
        return $initialize;
    }

    public function mapCheckoutFormInitializeResource(CheckoutFormInitializeResource $initialize)
    {
        return $this->mapCheckoutFormInitializeResourceFrom($initialize, $this->jsonObject);
    }
}