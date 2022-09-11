<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\IyziupFormInitializeResource;

class IyziupFormInitializeResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new IyziupFormInitializeResourceMapper($rawResult);
    }

    public function mapIyziupFormInitializeResourceFrom(IyziupFormInitializeResource $initialize, $jsonObject)
    {
        parent::mapResourceFrom($initialize, $jsonObject);

        if (array_key_exists($jsonObject->token)) {
            $initialize->setToken($jsonObject->token);
        }
        if (array_key_exists($jsonObject->content)) {
            $initialize->setContent($jsonObject->content);
        }
        if (array_key_exists($jsonObject->tokenExpireTime)) {
            $initialize->setTokenExpireTime($jsonObject->tokenExpireTime);
        }
        return $initialize;
    }

    public function mapIyziupFormInitializeResource(IyziupFormInitializeResource $initialize)
    {
        return $this->mapIyziupFormInitializeResourceFrom($initialize, $this->jsonObject);
    }
}