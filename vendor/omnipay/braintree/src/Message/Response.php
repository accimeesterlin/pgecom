<?php

namespace Omnipay\Braintree\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Response
 */
class Response extends AbstractResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        if (array_key_exists($this->data->success)) {
            return $this->data->success;
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        if (array_key_exists($this->data->message) && $this->data->message) {
            return $this->data->message;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->transactionValue('status');
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->transactionValue('id');
    }

    /**
     * @return string|null
     */
    public function getAmount()
    {
        return $this->transactionValue('amount');
    }

    /**
     * @return string|null
     */
    public function getTransactionId()
    {
        return $this->transactionValue('orderId');
    }

    /**
     * Return a value from the transaction object
     *
     * @param  string  $key
     * @return mixed
     */
    protected function transactionValue($key)
    {
        if (array_key_exists($this->data->transaction) && $this->data->transaction && array_key_exists($this->data->transaction->$key)) {
            return $this->data->transaction->$key;
        }

        return null;
    }
}
