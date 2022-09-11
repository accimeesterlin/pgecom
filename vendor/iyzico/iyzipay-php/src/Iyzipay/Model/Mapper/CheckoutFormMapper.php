<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\CheckoutForm;

class CheckoutFormMapper extends PaymentResourceMapper
{
    public static function create($rawResult = null)
    {
        return new CheckoutFormMapper($rawResult);
    }

    public function mapCheckoutFormFrom(CheckoutForm $auth, $jsonObject)
    {
        parent::mapPaymentResourceFrom($auth, $jsonObject);

        if (array_key_exists($jsonObject->token)) {
            $auth->setToken($jsonObject->token);
        }
        if (array_key_exists($jsonObject->callbackUrl)) {
            $auth->setCallbackUrl($jsonObject->callbackUrl);
        }
        return $auth;
    }

    public function mapCheckoutForm(CheckoutForm $auth)
    {
        return $this->mapCheckoutFormFrom($auth, $this->jsonObject);
    }
}