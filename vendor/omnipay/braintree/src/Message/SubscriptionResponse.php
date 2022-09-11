<?php

namespace Omnipay\Braintree\Message;

/**
 * Subscription Response
 */
class SubscriptionResponse extends Response
{
    public function getSubscriptionData()
    {
        if (array_key_exists($this->data->subscription)) {
            return $this->data->subscription;
        }

        return null;
    }
}
