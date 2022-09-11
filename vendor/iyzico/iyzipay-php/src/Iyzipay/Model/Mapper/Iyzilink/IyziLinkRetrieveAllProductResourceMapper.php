<?php

namespace Iyzipay\Model\Mapper\Iyzilink;

use Iyzipay\Model\Iyzilink\IyziLinkRetrieveAllProductResource;
use Iyzipay\Model\Mapper\IyzipayResourceMapper;

class IyziLinkRetrieveAllProductResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new IyzipayResourceMapper($rawResult);
    }

    public function mapIyziLinkRetriveAllProductResourceFrom(IyziLinkRetrieveAllProductResource $create, $jsonObject)
    {
        parent::mapResourceFrom($create, $jsonObject);

        if (array_key_exists($jsonObject->data->listingReviewed)) {
            $create->setListingReviewed($jsonObject->data->listingReviewed);
        }
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
}