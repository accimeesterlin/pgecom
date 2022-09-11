<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\ReportingPaymentTransactionResource;

class ReportingPaymentTransactionResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new ReportingPaymentTransactionResourceMapper($rawResult);
    }

    public function mapReportingPaymentTransactionResourceFrom(ReportingPaymentTransactionResource $create, $jsonObject)
    {
        parent::mapResourceFrom($create, $jsonObject);

        if (array_key_exists($jsonObject->transactions)) {

            $create->setTransactions($jsonObject->transactions);
        }

        if (array_key_exists($jsonObject->currentPage)) {
            $create->setCurrentPage($jsonObject->currentPage);
        }

        if (array_key_exists($jsonObject->totalPageCount)) {
            $create->setTotalPageCount($jsonObject->totalPageCount);
        }

        return $create;
    }

    public function mapReportingPaymentTransactionResource(ReportingPaymentTransactionResource $create)
    {
        return $this->mapReportingPaymentTransactionResourceFrom($create, $this->jsonObject);
    }
}