<?php

namespace Iyzipay\Model\Mapper\Subscription;

use Iyzipay\Model\Subscription\SubscriptionCardUpdate;
use Iyzipay\Model\Mapper\IyzipayResourceMapper;

class SubscriptionCardUpdateResourceMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new SubscriptionCardUpdateResourceMapper($rawResult);
    }

    public function mapSubscriptionCardUpdateResourceFrom(SubscriptionCardUpdate $create, $jsonObject)
    {
        parent::mapResourceFrom($create, $jsonObject);

        if (array_key_exists($jsonObject->token)) {
            $create->setToken($jsonObject->token);
        }
        if (array_key_exists($jsonObject->checkoutFormContent)) {
            $create->setCheckoutFormContent($jsonObject->checkoutFormContent);
        }
        if (array_key_exists($jsonObject->tokenExpireTime)) {
            $create->setTokenExpireTime($jsonObject->tokenExpireTime);
        }

        return $create;
    }

    public function mapSubscriptionCardUpdate(SubscriptionCardUpdate $subscriptionCardUpdate)
    {
        return $this->mapSubscriptionCardUpdateResourceFrom($subscriptionCardUpdate, $this->jsonObject);
    }
}