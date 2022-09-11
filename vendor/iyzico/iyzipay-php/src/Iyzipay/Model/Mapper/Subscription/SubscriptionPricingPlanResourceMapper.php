<?php

namespace Iyzipay\Model\Mapper\Subscription;

use Iyzipay\Model\Subscription\SubscriptionPricingPlan;
use Iyzipay\Model\Mapper\IyzipayResourceMapper;

class SubscriptionPricingPlanResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new SubscriptionPricingPlanResourceMapper($rawResult);
    }

    public function mapSubscriptionPricingPlanResourceFrom(SubscriptionPricingPlan $create, $jsonObject)
    {
        parent::mapResourceFrom($create, $jsonObject);

        if (array_key_exists($jsonObject->data->status)) {
            $create->setPricingPlanStatus($jsonObject->data->status);
        }
        if (array_key_exists($jsonObject->data->name)) {
            $create->setName($jsonObject->data->name);
        }
        if (array_key_exists($jsonObject->data->productReferenceCode)) {
            $create->setProductReferenceCode($jsonObject->data->productReferenceCode);
        }
        if (array_key_exists($jsonObject->data->price)) {
            $create->setPrice($jsonObject->data->price);
        }
        if (array_key_exists($jsonObject->data->currencyCode)) {
            $create->setCurrencyCode($jsonObject->data->currencyCode);
        }
        if (array_key_exists($jsonObject->data->paymentInterval)) {
            $create->setPaymentInterval($jsonObject->data->paymentInterval);
        }
        if (array_key_exists($jsonObject->data->paymentIntervalCount)) {
            $create->setPaymentIntervalCount($jsonObject->data->paymentIntervalCount);
        }
        if (array_key_exists($jsonObject->data->trialPeriodDays)) {
            $create->setTrialPeriodDays($jsonObject->data->trialPeriodDays);
        }
        if (array_key_exists($jsonObject->data->planPaymentType)) {
            $create->setPlanPaymentType($jsonObject->data->planPaymentType);
        }
        if (array_key_exists($jsonObject->data->recurrenceCount)) {
            $create->setRecurrenceCount($jsonObject->data->recurrenceCount);
        }
        if (array_key_exists($jsonObject->data->referenceCode)) {
            $create->setReferenceCode($jsonObject->data->referenceCode);
        }
        if (array_key_exists($jsonObject->data->createdDate)) {
            $create->setCreatedDate($jsonObject->data->createdDate);
        }
        return $create;
    }

    public function mapSubscriptionPricingPlan(SubscriptionPricingPlan $subscriptionPricingPlan)
    {
        return $this->mapSubscriptionPricingPlanResourceFrom($subscriptionPricingPlan, $this->jsonObject);
    }

}