<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\InstallmentDetail;
use Iyzipay\Model\InstallmentInfo;
use Iyzipay\Model\InstallmentPrice;

class InstallmentInfoMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new InstallmentInfoMapper($rawResult);
    }

    public function mapInstallmentInfoFrom(InstallmentInfo $installment, $jsonObject)
    {
        parent::mapResourceFrom($installment, $jsonObject);

        if (array_key_exists($jsonObject->installmentDetails)) {
            $installment->setInstallmentDetails($this->mapInstallmentDetails($jsonObject->installmentDetails));
        }
        return $installment;
    }

    public function mapInstallmentInfo(InstallmentInfo $installment)
    {
        return $this->mapInstallmentInfoFrom($installment, $this->jsonObject);
    }

    private function mapInstallmentDetails($installmentDetails)
    {
        $details = array();

        foreach ($installmentDetails as $index => $installmentDetail) {
            $detail = new InstallmentDetail();

            if (array_key_exists($installmentDetail->binNumber)) {
                $detail->setBinNumber($installmentDetail->binNumber);
            }
            if (array_key_exists($installmentDetail->price)) {
                $detail->setPrice($installmentDetail->price);
            }
            if (array_key_exists($installmentDetail->cardType)) {
                $detail->setCardType($installmentDetail->cardType);
            }
            if (array_key_exists($installmentDetail->cardAssociation)) {
                $detail->setCardAssociation($installmentDetail->cardAssociation);
            }
            if (array_key_exists($installmentDetail->cardFamilyName)) {
                $detail->setCardFamilyName($installmentDetail->cardFamilyName);
            }
            if (array_key_exists($installmentDetail->force3ds)) {
                $detail->setForce3ds($installmentDetail->force3ds);
            }
            if (array_key_exists($installmentDetail->bankCode)) {
                $detail->setBankCode($installmentDetail->bankCode);
            }
            if (array_key_exists($installmentDetail->bankName)) {
                $detail->setBankName($installmentDetail->bankName);
            }
            if (array_key_exists($installmentDetail->forceCvc)) {
                $detail->setForceCvc($installmentDetail->forceCvc);
            }
            if (array_key_exists($installmentDetail->commercial)) {
                $detail->setCommercial($installmentDetail->commercial);
            }
            if (array_key_exists($installmentDetail->installmentPrices)) {
                $detail->setInstallmentPrices($this->mapInstallmentPrices($installmentDetail->installmentPrices));
            }
            $details[$index] = $detail;
        }
        return $details;
    }

    private function mapInstallmentPrices($installmentPrices)
    {
        $prices = array();

        foreach ($installmentPrices as $index => $installmentPrice) {
            $price = new InstallmentPrice();

            if (array_key_exists($installmentPrice->installmentPrice)) {
                $price->setInstallmentPrice($installmentPrice->installmentPrice);
            }
            if (array_key_exists($installmentPrice->totalPrice)) {
                $price->setTotalPrice($installmentPrice->totalPrice);
            }
            if (array_key_exists($installmentPrice->installmentNumber)) {
                $price->setInstallmentNumber($installmentPrice->installmentNumber);
            }
            $prices[$index] = $price;
        }
        return $prices;
    }
}