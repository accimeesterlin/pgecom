<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\RefundResource;

class RefundResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new RefundResourceMapper($rawResult);
    }

    public function mapRefundResourceFrom(RefundResource $refundResource, $jsonObject)
    {
        parent::mapResourceFrom($refundResource, $jsonObject);

        if (array_key_exists($jsonObject->paymentId)) {
            $refundResource->setPaymentId($jsonObject->paymentId);
        }
        if (array_key_exists($jsonObject->paymentTransactionId)) {
            $refundResource->setPaymentTransactionId($jsonObject->paymentTransactionId);
        }
        if (array_key_exists($jsonObject->price)) {
            $refundResource->setPrice($jsonObject->price);
        }
        if (array_key_exists($jsonObject->currency)) {
            $refundResource->setCurrency($jsonObject->currency);
        }
        if (array_key_exists($jsonObject->connectorName)) {
            $refundResource->setConnectorName($jsonObject->connectorName);
        }
        if (array_key_exists($jsonObject->authCode)) {
            $refundResource->setAuthCode($jsonObject->authCode);
        }
        return $refundResource;
    }

    public function mapRefundResource(RefundResource $refundResource)
    {
        return $this->mapRefundResourceFrom($refundResource, $this->jsonObject);
    }
}