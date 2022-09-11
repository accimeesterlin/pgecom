<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\UCSInitializeResource;

class UCSInitializeResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new UCSInitializeMapper($rawResult);
    }

    public function mapUCSInitializeResourceFrom(UCSInitializeResource $initialize, $jsonObject)
    {
        parent::mapResourceFrom($initialize, $jsonObject);

        if (array_key_exists($jsonObject->ucsToken)) {
            $initialize->setUcsToken($jsonObject->ucsToken);
        }
        if (array_key_exists($jsonObject->buyerProtectedConsumer)) {
            $initialize->setBuyerProtectedConsumer($jsonObject->buyerProtectedConsumer);
        }
        if (array_key_exists($jsonObject->buyerProtectedMerchant)) {
            $initialize->setBuyerProtectedMerchant($jsonObject->buyerProtectedMerchant);
        }
        if (array_key_exists($jsonObject->gsmNumber)) {
            $initialize->setGsmNumber($jsonObject->gsmNumber);
        }
        if (array_key_exists($jsonObject->maskedGsmNumber)) {
            $initialize->setMaskedGsmNumber($jsonObject->maskedGsmNumber);
        }
        if (array_key_exists($jsonObject->merchantName)) {
            $initialize->setMerchantName($jsonObject->merchantName);
        }
        if (array_key_exists($jsonObject->script)) {
            $initialize->setScript($jsonObject->script);
        }
        if (array_key_exists($jsonObject->scriptType)) {
            $initialize->setScriptType($jsonObject->scriptType);
        }
        return $initialize;
    }

    public function mapUCSInitializeResource(UCSInitializeResource $initialize)
    {
        return $this->mapUCSInitializeResourceFrom($initialize, $this->jsonObject);
    }
}