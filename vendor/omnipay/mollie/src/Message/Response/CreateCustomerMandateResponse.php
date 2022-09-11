<?php

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/mandates-api/create-mandate
 */
class CreateCustomerMandateResponse extends AbstractMollieResponse
{
    /**
     * @return string
     */
    public function getMandateId()
    {
        if (array_key_exists($this->data['id'])) {
            return $this->data['id'];
        }
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return array_key_exists($this->data['id']);
    }
}
