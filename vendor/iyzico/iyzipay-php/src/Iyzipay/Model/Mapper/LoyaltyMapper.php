<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\Loyalty;

class LoyaltyMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new LoyaltyMapper($rawResult);
    }

    public function mapLoyaltyFrom(Loyalty $loyalty, $jsonObject)
    {
        parent::mapResourceFrom($loyalty, $jsonObject);

        if (array_key_exists($jsonObject->points)) {
            $loyalty->setPoints($jsonObject->points);
        }
        if (array_key_exists($jsonObject->amount)) {
            $loyalty->setAmount($jsonObject->amount);
        }
        if (array_key_exists($jsonObject->cardBank)) {
            $loyalty->setCardBank($jsonObject->cardBank);
        }
        if (array_key_exists($jsonObject->cardFamily)) {
            $loyalty->setCardFamily($jsonObject->cardFamily);
        }
        if (array_key_exists($jsonObject->currency)) {
            $loyalty->setCurrency($jsonObject->currency);
        }

        return $loyalty;
    }

    public function mapLoyalty(Loyalty $loyalty)
    {
        return $this->mapLoyaltyFrom($loyalty, $this->jsonObject);
    }
}