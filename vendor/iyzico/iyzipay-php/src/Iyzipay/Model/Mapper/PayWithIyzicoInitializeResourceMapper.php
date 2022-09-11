<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\PayWithIyzicoInitializeResource;

class PayWithIyzicoInitializeResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new PayWithIyzicoInitializeMapper($rawResult);
    }

    public function mapPayWithIyzicoInitializeResourceFrom(PayWithIyzicoInitializeResource $initialize, $jsonObject)
    {
        parent::mapResourceFrom($initialize, $jsonObject);

        if (array_key_exists($jsonObject->token)) {
            $initialize->setToken($jsonObject->token);
        }
        if (array_key_exists($jsonObject->payWithIyzicoContent)) {
            $initialize->setPayWithIyzicoContent($jsonObject->payWithIyzicoContent);
        }
        if (array_key_exists($jsonObject->tokenExpireTime)) {
            $initialize->setTokenExpireTime($jsonObject->tokenExpireTime);
        }
        if (array_key_exists($jsonObject->payWithIyzicoPageUrl)) {
            $initialize->setPaymentPageUrl($jsonObject->payWithIyzicoPageUrl);
        }
        return $initialize;
    }

    public function mapPayWithIyzicoInitializeResource(PayWithIyzicoInitializeResource $initialize)
    {
        return $this->mapPayWithIyzicoInitializeResourceFrom($initialize, $this->jsonObject);
    }
}