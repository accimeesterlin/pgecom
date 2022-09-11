<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\Card;
use Iyzipay\Model\CardList;

class CardListMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new CardListMapper($rawResult);
    }

    public function mapCardListFrom(CardList $cardList, $jsonObject)
    {
        parent::mapResourceFrom($cardList, $jsonObject);

        if (array_key_exists($jsonObject->cardUserKey)) {
            $cardList->setCardUserKey($jsonObject->cardUserKey);
        }
        if (array_key_exists($jsonObject->cardDetails)) {
            $cardList->setCardDetails($this->mapCardDetails($jsonObject->cardDetails));
        }
        return $cardList;
    }

    public function mapCardList(CardList $cardList)
    {
        return $this->mapCardListFrom($cardList, $this->jsonObject);
    }

    private function mapCardDetails($cardDetails)
    {
        $cards = array();

        foreach ($cardDetails as $index => $cardDetail) {
            $cards[$index] = CardMapper::create()->mapCardFrom(new Card(), $cardDetail);
        }
        return $cards;
    }
}