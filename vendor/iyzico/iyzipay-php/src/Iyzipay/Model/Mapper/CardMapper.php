<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\Card;

class CardMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new CardMapper($rawResult);
    }

    public function mapCardFrom(Card $card, $jsonObject)
    {
        parent::mapResourceFrom($card, $jsonObject);

        if (array_key_exists($jsonObject->externalId)) {
            $card->setExternalId($jsonObject->externalId);
        }
        if (array_key_exists($jsonObject->email)) {
            $card->setEmail($jsonObject->email);
        }
        if (array_key_exists($jsonObject->cardUserKey)) {
            $card->setCardUserKey($jsonObject->cardUserKey);
        }
        if (array_key_exists($jsonObject->cardToken)) {
            $card->setCardToken($jsonObject->cardToken);
        }
        if (array_key_exists($jsonObject->cardAlias)) {
            $card->setCardAlias($jsonObject->cardAlias);
        }
        if (array_key_exists($jsonObject->binNumber)) {
            $card->setBinNumber($jsonObject->binNumber);
        }
        if (array_key_exists($jsonObject->cardType)) {
            $card->setCardType($jsonObject->cardType);
        }
        if (array_key_exists($jsonObject->cardAssociation)) {
            $card->setCardAssociation($jsonObject->cardAssociation);
        }
        if (array_key_exists($jsonObject->cardFamily)) {
            $card->setCardFamily($jsonObject->cardFamily);
        }
        if (array_key_exists($jsonObject->cardBankCode)) {
            $card->setCardBankCode($jsonObject->cardBankCode);
        }
        if (array_key_exists($jsonObject->cardBankName)) {
            $card->setCardBankName($jsonObject->cardBankName);
        }
        return $card;
    }

    public function mapCard(Card $card)
    {
        return $this->mapCardFrom($card, $this->jsonObject);
    }
}