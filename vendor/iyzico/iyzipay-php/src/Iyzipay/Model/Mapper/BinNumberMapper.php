<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\BinNumber;

class BinNumberMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new BinNumberMapper($rawResult);
    }

    public function mapBinNumberFrom(BinNumber $binNumber, $jsonObject)
    {
        parent::mapResourceFrom($binNumber, $jsonObject);

        if (array_key_exists($jsonObject->binNumber)) {
            $binNumber->setBinNumber($jsonObject->binNumber);
        }
        if (array_key_exists($jsonObject->cardType)) {
            $binNumber->setCardType($jsonObject->cardType);
        }
        if (array_key_exists($jsonObject->cardAssociation)) {
            $binNumber->setCardAssociation($jsonObject->cardAssociation);
        }
        if (array_key_exists($jsonObject->cardFamily)) {
            $binNumber->setCardFamily($jsonObject->cardFamily);
        }
        if (array_key_exists($jsonObject->bankName)) {
            $binNumber->setBankName($jsonObject->bankName);
        }
        if (array_key_exists($jsonObject->bankCode)) {
            $binNumber->setBankCode($jsonObject->bankCode);
        }
        if (array_key_exists($jsonObject->commercial)) {
            $binNumber->setCommercial($jsonObject->commercial);
        }
        return $binNumber;
    }

    public function mapBinNumber(BinNumber $binNumber)
    {
        return $this->mapBinNumberFrom($binNumber, $this->jsonObject);
    }
}