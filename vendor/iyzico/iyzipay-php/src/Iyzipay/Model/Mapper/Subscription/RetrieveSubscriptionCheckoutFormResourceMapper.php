<?php

namespace Iyzipay\Model\Mapper\Subscription;

use Iyzipay\Model\Subscription\RetrieveSubscriptionCheckoutForm;
use Iyzipay\Model\Mapper\IyzipayResourceMapper;

class RetrieveSubscriptionCheckoutFormResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new RetrieveSubscriptionCheckoutFormResourceMapper($rawResult);
    }

    public function mapRetrieveSubscriptionCheckoutFormResourceFrom(RetrieveSubscriptionCheckoutForm $create, $jsonObject)
    {
        parent::mapResourceFrom($create, $jsonObject);

        if (array_key_exists($jsonObject->data->referenceCode)) {
            $create->setReferenceCode($jsonObject->data->referenceCode);
        }
        if (array_key_exists($jsonObject->data->parentReferenceCode)){
            $create->setParentReferenceCode($jsonObject->data->parentReferenceCode);
        }
        if(array_key_exists($jsonObject->data->pricingPlanReferenceCode)){
            $create->setPricingPlanReferenceCode($jsonObject->data->pricingPlanReferenceCode);
        }
        if(array_key_exists($jsonObject->data->customerReferenceCode)){
            $create->setCustomerReferenceCode($jsonObject->data->customerReferenceCode);
        }
        if(array_key_exists($jsonObject->data->subscriptionStatus)){
            $create->setSubscriptionStatus($jsonObject->data->subscriptionStatus);
        }
        if(array_key_exists($jsonObject->data->trialDays)){
            $create->setTrialDays($jsonObject->data->trialDays);
        }
        if(array_key_exists($jsonObject->data->trialStartDate)){
            $create->setTrialStartDate($jsonObject->data->trialStartDate);
        }
        if(array_key_exists($jsonObject->data->trialEndDate)){
            $create->setTrialEndDate($jsonObject->data->trialEndDate);
        }
        if(array_key_exists($jsonObject->data->createdDate)){
            $create->setCreatedDate($jsonObject->data->createdDate);
        }
        if(array_key_exists($jsonObject->data->startDate)){
            $create->setStartDate($jsonObject->data->startDate);
        }
        if(array_key_exists($jsonObject->data->endDate)){
            $create->setEndDate($jsonObject->data->endDate);
        }

        return $create;
    }

    public function mapRetrieveSubscriptionCheckoutForm(RetrieveSubscriptionCheckoutForm $retrieveCheckoutForm)
    {
        return $this->mapRetrieveSubscriptionCheckoutFormResourceFrom($retrieveCheckoutForm, $this->jsonObject);
    }
}