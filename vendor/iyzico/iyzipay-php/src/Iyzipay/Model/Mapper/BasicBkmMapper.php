<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\BasicBkm;

class BasicBkmMapper extends BasicPaymentResourceMapper
{
    public static function create($rawResult = null)
    {
        return new BasicBkmMapper($rawResult);
    }

    public function mapBasicBkmFrom(BasicBkm $auth, $jsonObject)
    {
        parent::mapBasicPaymentResourceFrom($auth, $jsonObject);

        if (array_key_exists($jsonObject->token)) {
            $auth->setToken($jsonObject->token);
        }
        if (array_key_exists($jsonObject->callbackUrl)) {
            $auth->setCallbackUrl($jsonObject->callbackUrl);
        }
        if (array_key_exists($jsonObject->paymentStatus)) {
            $auth->setPaymentStatus($jsonObject->paymentStatus);
        }
        return $auth;
    }

    public function mapBasicBkm(BasicBkm $auth)
    {
        return $this->mapBasicBkmFrom($auth, $this->jsonObject);
    }
}