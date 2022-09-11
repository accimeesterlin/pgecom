<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\Cancel;

class CancelMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new CancelMapper($rawResult);
    }

    public function mapCancelFrom(Cancel $cancel, $jsonObject)
    {
        parent::mapResourceFrom($cancel, $jsonObject);

        if (array_key_exists($jsonObject->paymentId)) {
            $cancel->setPaymentId($jsonObject->paymentId);
        }
        if (array_key_exists($jsonObject->price)) {
            $cancel->setPrice($jsonObject->price);
        }
        if (array_key_exists($jsonObject->currency)) {
            $cancel->setCurrency($jsonObject->currency);
        }
        if (array_key_exists($jsonObject->connectorName)) {
            $cancel->setConnectorName($jsonObject->connectorName);
        }
        if (array_key_exists($jsonObject->authCode)) {
            $cancel->setAuthCode($jsonObject->authCode);
        }
        return $cancel;
    }

    public function mapCancel(Cancel $cancel)
    {
        return $this->mapCancelFrom($cancel, $this->jsonObject);
    }
}