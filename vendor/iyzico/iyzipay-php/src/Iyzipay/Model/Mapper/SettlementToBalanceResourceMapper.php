<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\SettlementToBalanceResource;

class SettlementToBalanceResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new SettlementToBalanceResourceMapper($rawResult);
    }

    public function mapSettlementToBalanceResourceFrom(SettlementToBalanceResource $settlementToBalanceResource, $jsonObject)
    {
        parent::mapResourceFrom($settlementToBalanceResource, $jsonObject);

        if (array_key_exists($jsonObject->url)) {
            $settlementToBalanceResource->setUrl($jsonObject->url);
        }
        if (array_key_exists($jsonObject->token)) {
            $settlementToBalanceResource->setToken($jsonObject->token);
        }
        if (array_key_exists($jsonObject->settingsAllTime)) {
            $settlementToBalanceResource->setSettingsAllTime($jsonObject->settingsAllTime);
        }

        return $settlementToBalanceResource;
    }

    public function mapSettlementToBalanceResource(SettlementToBalanceResource $settlementToBalanceResource)
    {
        return $this->mapSettlementToBalanceResourceFrom($settlementToBalanceResource, $this->jsonObject);
    }
}