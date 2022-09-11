<?php

namespace Iyzipay\Model\Mapper\Subscription;

use Iyzipay\Model\Subscription\RetrieveList;
use Iyzipay\Model\Mapper\IyzipayResourceMapper;

class RetrieveListResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new RetrieveListResourceMapper($rawResult);
    }

    public function mapRetrieveListResourceFrom(RetrieveList $create, $jsonObject)
    {
        parent::mapResourceFrom($create, $jsonObject);

        if (array_key_exists($jsonObject->data->totalCount)) {
            $create->setTotalCount($jsonObject->data->totalCount);
        }
        if (array_key_exists($jsonObject->data->currentPage)) {
            $create->setCurrentPage($jsonObject->data->currentPage);
        }
        if (array_key_exists($jsonObject->data->pageCount)) {
            $create->setPageCount($jsonObject->data->pageCount);
        }
        if (array_key_exists($jsonObject->data->items)) {
            $create->setItems($jsonObject->data->items);
        }

        return $create;
    }

    public function mapRetrieveList(RetrieveList $retrieveList)
    {
        return $this->mapRetrieveListResourceFrom($retrieveList, $this->jsonObject);
    }

}