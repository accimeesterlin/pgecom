<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\SubMerchant;

class SubMerchantMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new SubMerchantMapper($rawResult);
    }

    public function mapSubMerchantFrom(SubMerchant $subMerchant, $jsonObject)
    {
        parent::mapResourceFrom($subMerchant, $jsonObject);

        if (array_key_exists($jsonObject->name)) {
            $subMerchant->setName($jsonObject->name);
        }
        if (array_key_exists($jsonObject->email)) {
            $subMerchant->setEmail($jsonObject->email);
        }
        if (array_key_exists($jsonObject->gsmNumber)) {
            $subMerchant->setGsmNumber($jsonObject->gsmNumber);
        }
        if (array_key_exists($jsonObject->address)) {
            $subMerchant->setAddress($jsonObject->address);
        }
        if (array_key_exists($jsonObject->iban)) {
            $subMerchant->setIban($jsonObject->iban);
        }
        if (array_key_exists($jsonObject->taxOffice)) {
            $subMerchant->setTaxOffice($jsonObject->taxOffice);
        }
        if (array_key_exists($jsonObject->contactName)) {
            $subMerchant->setContactName($jsonObject->contactName);
        }
        if (array_key_exists($jsonObject->contactSurname)) {
            $subMerchant->setContactSurname($jsonObject->contactSurname);
        }
        if (array_key_exists($jsonObject->legalCompanyTitle)) {
            $subMerchant->setLegalCompanyTitle($jsonObject->legalCompanyTitle);
        }
        if (array_key_exists($jsonObject->subMerchantExternalId)) {
            $subMerchant->setSubMerchantExternalId($jsonObject->subMerchantExternalId);
        }
        if (array_key_exists($jsonObject->identityNumber)) {
            $subMerchant->setIdentityNumber($jsonObject->identityNumber);
        }
        if (array_key_exists($jsonObject->taxNumber)) {
            $subMerchant->setTaxNumber($jsonObject->taxNumber);
        }
        if (array_key_exists($jsonObject->subMerchantType)) {
            $subMerchant->setSubMerchantType($jsonObject->subMerchantType);
        }
        if (array_key_exists($jsonObject->subMerchantKey)) {
            $subMerchant->setSubMerchantKey($jsonObject->subMerchantKey);
        }
        if (array_key_exists($jsonObject->swiftCode)) {
            $subMerchant->setSwiftCode($jsonObject->swiftCode);
        }
        if (array_key_exists($jsonObject->currency)) {
            $subMerchant->setCurrency($jsonObject->currency);
        }
        return $subMerchant;
    }

    public function mapSubMerchant(SubMerchant $subMerchant)
    {
        return $this->mapSubMerchantFrom($subMerchant, $this->jsonObject);
    }
}