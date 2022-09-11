<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\PaymentResource;

class PaymentResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new PaymentResourceMapper($rawResult);
    }

    public function mapPaymentResourceFrom(PaymentResource $paymentResource, $jsonObject)
    {
        parent::mapResourceFrom($paymentResource, $jsonObject);

        if (array_key_exists($jsonObject->price)) {
            $paymentResource->setPrice($jsonObject->price);
        }
        if (array_key_exists($jsonObject->paidPrice)) {
            $paymentResource->setPaidPrice($jsonObject->paidPrice);
        }
        if (array_key_exists($jsonObject->installment)) {
            $paymentResource->setInstallment($jsonObject->installment);
        }
        if (array_key_exists($jsonObject->paymentId)) {
            $paymentResource->setPaymentId($jsonObject->paymentId);
        }
        if (array_key_exists($jsonObject->paymentStatus)) {
            $paymentResource->setPaymentStatus($jsonObject->paymentStatus);
        }
        if (array_key_exists($jsonObject->fraudStatus)) {
            $paymentResource->setFraudStatus($jsonObject->fraudStatus);
        }
        if (array_key_exists($jsonObject->merchantCommissionRate)) {
            $paymentResource->setMerchantCommissionRate($jsonObject->merchantCommissionRate);
        }
        if (array_key_exists($jsonObject->merchantCommissionRateAmount)) {
            $paymentResource->setMerchantCommissionRateAmount($jsonObject->merchantCommissionRateAmount);
        }
        if (array_key_exists($jsonObject->iyziCommissionRateAmount)) {
            $paymentResource->setIyziCommissionRateAmount($jsonObject->iyziCommissionRateAmount);
        }
        if (array_key_exists($jsonObject->iyziCommissionFee)) {
            $paymentResource->setIyziCommissionFee($jsonObject->iyziCommissionFee);
        }
        if (array_key_exists($jsonObject->cardType)) {
            $paymentResource->setCardType($jsonObject->cardType);
        }
        if (array_key_exists($jsonObject->cardAssociation)) {
            $paymentResource->setCardAssociation($jsonObject->cardAssociation);
        }
        if (array_key_exists($jsonObject->cardFamily)) {
            $paymentResource->setCardFamily($jsonObject->cardFamily);
        }
        if (array_key_exists($jsonObject->cardUserKey)) {
            $paymentResource->setCardUserKey($jsonObject->cardUserKey);
        }
        if (array_key_exists($jsonObject->cardToken)) {
            $paymentResource->setCardToken($jsonObject->cardToken);
        }
        if (array_key_exists($jsonObject->binNumber)) {
            $paymentResource->setBinNumber($jsonObject->binNumber);
        }
        if (array_key_exists($jsonObject->basketId)) {
            $paymentResource->setBasketId($jsonObject->basketId);
        }
        if (array_key_exists($jsonObject->currency)) {
            $paymentResource->setCurrency($jsonObject->currency);
        }
        if (array_key_exists($jsonObject->itemTransactions)) {
            $paymentResource->setPaymentItems(PaymentItemMapper::create()->mapPaymentItems($jsonObject->itemTransactions));
        }
        if (array_key_exists($jsonObject->connectorName)) {
            $paymentResource->setConnectorName($jsonObject->connectorName);
        }
        if (array_key_exists($jsonObject->authCode)) {
            $paymentResource->setAuthCode($jsonObject->authCode);
        }
        if (array_key_exists($jsonObject->phase)) {
            $paymentResource->setPhase($jsonObject->phase);
        }
        if (array_key_exists($jsonObject->lastFourDigits)) {
            $paymentResource->setLastFourDigits($jsonObject->lastFourDigits);
        }
        if (array_key_exists($jsonObject->posOrderId)) {
            $paymentResource->setPosOrderId($jsonObject->posOrderId);
        }
        return $paymentResource;
    }

    public function mapPaymentResource(PaymentResource $paymentResource)
    {
        return $this->mapPaymentResourceFrom($paymentResource, $this->jsonObject);
    }
}