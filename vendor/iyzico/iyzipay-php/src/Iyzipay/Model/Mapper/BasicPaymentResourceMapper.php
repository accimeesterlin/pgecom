<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\BasicPaymentResource;

class BasicPaymentResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new BasicPaymentResourceMapper($rawResult);
    }

    public function mapBasicPaymentResourceFrom(BasicPaymentResource $payment, $jsonObject)
    {
        parent::mapResourceFrom($payment, $jsonObject);

        if (array_key_exists($jsonObject->price)) {
            $payment->setPrice($jsonObject->price);
        }
        if (array_key_exists($jsonObject->paidPrice)) {
            $payment->setPaidPrice($jsonObject->paidPrice);
        }
        if (array_key_exists($jsonObject->installment)) {
            $payment->setInstallment($jsonObject->installment);
        }
        if (array_key_exists($jsonObject->paymentId)) {
            $payment->setPaymentId($jsonObject->paymentId);
        }
        if (array_key_exists($jsonObject->merchantCommissionRate)) {
            $payment->setMerchantCommissionRate($jsonObject->merchantCommissionRate);
        }
        if (array_key_exists($jsonObject->merchantCommissionRateAmount)) {
            $payment->setMerchantCommissionRateAmount($jsonObject->merchantCommissionRateAmount);
        }
        if (array_key_exists($jsonObject->iyziCommissionFee)) {
            $payment->setIyziCommissionFee($jsonObject->iyziCommissionFee);
        }
        if (array_key_exists($jsonObject->cardType)) {
            $payment->setCardType($jsonObject->cardType);
        }
        if (array_key_exists($jsonObject->cardAssociation)) {
            $payment->setCardAssociation($jsonObject->cardAssociation);
        }
        if (array_key_exists($jsonObject->cardFamily)) {
            $payment->setCardFamily($jsonObject->cardFamily);
        }
        if (array_key_exists($jsonObject->cardToken)) {
            $payment->setCardToken($jsonObject->cardToken);
        }
        if (array_key_exists($jsonObject->cardUserKey)) {
            $payment->setCardUserKey($jsonObject->cardUserKey);
        }
        if (array_key_exists($jsonObject->binNumber)) {
            $payment->setBinNumber($jsonObject->binNumber);
        }
        if (array_key_exists($jsonObject->paymentTransactionId)) {
            $payment->setPaymentTransactionId($jsonObject->paymentTransactionId);
        }
        if (array_key_exists($jsonObject->authCode)) {
            $payment->setAuthCode($jsonObject->authCode);
        }
        if (array_key_exists($jsonObject->connectorName)) {
            $payment->setConnectorName($jsonObject->connectorName);
        }
        if (array_key_exists($jsonObject->currency)) {
            $payment->setCurrency($jsonObject->currency);
        }
        if (array_key_exists($jsonObject->phase)) {
            $payment->setPhase($jsonObject->phase);
        }
        return $payment;
    }

    public function mapBasicPaymentResource(BasicPaymentResource $payment)
    {
        return $this->mapBasicPaymentResourceFrom($payment, $this->jsonObject);
    }
}