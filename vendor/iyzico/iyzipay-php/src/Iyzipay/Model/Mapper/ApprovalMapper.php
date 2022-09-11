<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\Approval;

class ApprovalMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new ApprovalMapper($rawResult);
    }

    public function mapApprovalFrom(Approval $approval, $jsonObject)
    {
        parent::mapResourceFrom($approval, $jsonObject);

        if (array_key_exists($jsonObject->paymentTransactionId)) {
            $approval->setPaymentTransactionId($jsonObject->paymentTransactionId);
        }
        return $approval;
    }

    public function mapApproval(Approval $approval)
    {
        return $this->mapApprovalFrom($approval, $this->jsonObject);
    }
}