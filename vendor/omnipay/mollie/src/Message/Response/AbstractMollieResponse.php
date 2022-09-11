<?php

namespace Omnipay\Mollie\Message\Response;

use Omnipay\Common\Message\AbstractResponse;

class AbstractMollieResponse extends AbstractResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        if (array_key_exists($this->data['status']) && array_key_exists($this->data['detail'])) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return json_encode($this->data);
    }
}
