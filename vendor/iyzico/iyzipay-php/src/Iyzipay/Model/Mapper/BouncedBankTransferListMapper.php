<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\BankTransfer;
use Iyzipay\Model\BouncedBankTransferList;

class BouncedBankTransferListMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new BouncedBankTransferListMapper($rawResult);
    }

    public function mapBouncedBankTransferListFrom(BouncedBankTransferList $transferList, $jsonObject)
    {
        parent::mapResourceFrom($transferList, $jsonObject);

        if (array_key_exists($jsonObject->bouncedRows)) {
            $transferList->setBankTransfers($this->mapBankTransfers($jsonObject->bouncedRows));
        }
        return $transferList;
    }

    public function mapBouncedBankTransferList(BouncedBankTransferList $transferList)
    {
        return $this->mapBouncedBankTransferListFrom($transferList, $this->jsonObject);
    }

    private function mapBankTransfers($bouncedRows)
    {
        $bankTransfers = array();

        foreach ($bouncedRows as $index => $bouncedRow) {
            $bankTransfer = new BankTransfer();

            if (array_key_exists($bouncedRow->subMerchantKey)) {
                $bankTransfer->setSubMerchantKey($bouncedRow->subMerchantKey);
            }
            if (array_key_exists($bouncedRow->iban)) {
                $bankTransfer->setIban($bouncedRow->iban);
            }
            if (array_key_exists($bouncedRow->contactName)) {
                $bankTransfer->setContactName($bouncedRow->contactName);
            }
            if (array_key_exists($bouncedRow->contactSurname)) {
                $bankTransfer->setContactSurname($bouncedRow->contactSurname);
            }
            if (array_key_exists($bouncedRow->legalCompanyTitle)) {
                $bankTransfer->setLegalCompanyTitle($bouncedRow->legalCompanyTitle);
            }
            if (array_key_exists($bouncedRow->marketplaceSubmerchantType)) {
                $bankTransfer->setMarketplaceSubMerchantType($bouncedRow->marketplaceSubmerchantType);
            }
            $bankTransfers[$index] = $bankTransfer;
        }
        return $bankTransfers;
    }
}