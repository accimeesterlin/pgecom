<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\ApmResource;

class ApmResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new ApmResourceMapper($rawResult);
    }

    public function mapApmResourceFrom(ApmResource $apmResource, $jsonObject)
    {
        parent::mapResourceFrom($apmResource, $jsonObject);

        if (array_key_exists($jsonObject->redirectUrl)) {
            $apmResource->setRedirectUrl($jsonObject->redirectUrl);
        }
        if (array_key_exists($jsonObject->price)) {
            $apmResource->setPrice($jsonObject->price);
        }
        if (array_key_exists($jsonObject->paidPrice)) {
            $apmResource->setPaidPrice($jsonObject->paidPrice);
        }
        if (array_key_exists($jsonObject->paymentId)) {
            $apmResource->setPaymentId($jsonObject->paymentId);
        }
        if (array_key_exists($jsonObject->merchantCommissionRate)) {
            $apmResource->setMerchantCommissionRate($jsonObject->merchantCommissionRate);
        }
        if (array_key_exists($jsonObject->merchantCommissionRateAmount)) {
            $apmResource->setMerchantCommissionRateAmount($jsonObject->merchantCommissionRateAmount);
        }
        if (array_key_exists($jsonObject->iyziCommissionRateAmount)) {
            $apmResource->setIyziCommissionRateAmount($jsonObject->iyziCommissionRateAmount);
        }
        if (array_key_exists($jsonObject->iyziCommissionFee)) {
            $apmResource->setIyziCommissionFee($jsonObject->iyziCommissionFee);
        }
        if (array_key_exists($jsonObject->basketId)) {
            $apmResource->setBasketId($jsonObject->basketId);
        }
        if (array_key_exists($jsonObject->currency)) {
            $apmResource->setCurrency($jsonObject->currency);
        }
        if (array_key_exists($jsonObject->itemTransactions)) {
            $apmResource->setPaymentItems(PaymentItemMapper::create()->mapPaymentItems($jsonObject->itemTransactions));
        }
        if (array_key_exists($jsonObject->phase)) {
            $apmResource->setPhase($jsonObject->phase);
        }
        if (array_key_exists($jsonObject->accountHolderName)) {
            $apmResource->setAccountHolderName($jsonObject->accountHolderName);
        }
        if (array_key_exists($jsonObject->accountNumber)) {
            $apmResource->setAccountNumber($jsonObject->accountNumber);
        }
        if (array_key_exists($jsonObject->bankName)) {
            $apmResource->setBankName($jsonObject->bankName);
        }
        if (array_key_exists($jsonObject->bankCode)) {
            $apmResource->setBankCode($jsonObject->bankCode);
        }
        if (array_key_exists($jsonObject->bic)) {
            $apmResource->setBic($jsonObject->bic);
        }
        if (array_key_exists($jsonObject->paymentPurpose)) {
            $apmResource->setPaymentPurpose($jsonObject->paymentPurpose);
        }
        if (array_key_exists($jsonObject->iban)) {
            $apmResource->setIban($jsonObject->iban);
        }
        if (array_key_exists($jsonObject->countryCode)) {
            $apmResource->setCountryCode($jsonObject->countryCode);
        }
        if (array_key_exists($jsonObject->apm)) {
            $apmResource->setApm($jsonObject->apm);
        }
        if (array_key_exists($jsonObject->mobilePhone)) {
            $apmResource->setMobilePhone($jsonObject->mobilePhone);
        }
        if (array_key_exists($jsonObject->paymentStatus)) {
            $apmResource->setPaymentStatus($jsonObject->paymentStatus);
        }
        return $apmResource;
    }

    public function mapApmResource(ApmResource $apmResource)
    {
        return $this->mapApmResourceFrom($apmResource, $this->jsonObject);
    }
}