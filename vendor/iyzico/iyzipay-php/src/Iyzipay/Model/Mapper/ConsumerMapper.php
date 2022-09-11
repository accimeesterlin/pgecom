<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\Consumer;

class ConsumerMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new ConsumerMapper($rawResult);
    }

    public function mapConsumerFrom(Consumer $consumer, $jsonObject)
    {
        if (array_key_exists($jsonObject->name)) {
            $consumer->setName($jsonObject->name);
        }
        if (array_key_exists($jsonObject->surname)) {
            $consumer->setSurname($jsonObject->surname);
        }
        if (array_key_exists($jsonObject->identityNumber)) {
            $consumer->setIdentityNumber($jsonObject->identityNumber);
        }
        if (array_key_exists($jsonObject->email)) {
            $consumer->setEmail($jsonObject->email);
        }
        if (array_key_exists($jsonObject->gsmNumber)) {
            $consumer->setGsmNumber($jsonObject->gsmNumber);
        }
        return $consumer;
    }
}