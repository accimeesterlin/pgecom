<?php

namespace Iyzipay\Model\Mapper\Subscription;

use Iyzipay\Model\Subscription\SubscriptionProduct;
use Iyzipay\Model\Mapper\IyzipayResourceMapper;

class SubscriptionProductResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new SubscriptionProductResourceMapper($rawResult);
    }

    public function mapSubscriptionProductResourceFrom(SubscriptionProduct $create, $jsonObject)
    {
        parent::mapResourceFrom($create, $jsonObject);

        if (array_key_exists($jsonObject->status)) {
            $create->setStatus($jsonObject->status);
        }
        if (array_key_exists($jsonObject->data->name)) {
            $create->setName($jsonObject->data->name);
        }
        if (array_key_exists($jsonObject->data->description)) {
            $create->setDescription($jsonObject->data->description);
        }
        if (array_key_exists($jsonObject->data->status)) {
            $create->setProductStatus($jsonObject->data->status);
        }

        if (array_key_exists($jsonObject->data->referenceCode)) {
            $create->setReferenceCode($jsonObject->data->referenceCode);
        }

        if (array_key_exists($jsonObject->data->pricingPlans)) {
            $create->setPricingPlans($jsonObject->data->pricingPlans);
        }

        if (array_key_exists($jsonObject->data->createdDate)) {
            $create->setCreatedDate($jsonObject->data->createdDate);
        }
        return $create;
    }

    public function mapSubscriptionProduct(SubscriptionProduct $subscriptionProduct)
    {
        return $this->mapSubscriptionProductResourceFrom($subscriptionProduct, $this->jsonObject);
    }

}