<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\ConvertedPayout;
use Iyzipay\Model\PaymentItem;

class PaymentItemMapper
{
    public static function create()
    {
        return new PaymentItemMapper();
    }

    public function mapPaymentItems($itemTransactions)
    {
        $paymentItems = array();

        foreach ($itemTransactions as $index => $itemTransaction) {
            $paymentItem = new PaymentItem();

            if (array_key_exists($itemTransaction->itemId)) {
                $paymentItem->setItemId($itemTransaction->itemId);
            }
            if (array_key_exists($itemTransaction->paymentTransactionId)) {
                $paymentItem->setPaymentTransactionId($itemTransaction->paymentTransactionId);
            }
            if (array_key_exists($itemTransaction->transactionStatus)) {
                $paymentItem->setTransactionStatus($itemTransaction->transactionStatus);
            }
            if (array_key_exists($itemTransaction->price)) {
                $paymentItem->setPrice($itemTransaction->price);
            }
            if (array_key_exists($itemTransaction->paidPrice)) {
                $paymentItem->setPaidPrice($itemTransaction->paidPrice);
            }
            if (array_key_exists($itemTransaction->merchantCommissionRate)) {
                $paymentItem->setMerchantCommissionRate($itemTransaction->merchantCommissionRate);
            }
            if (array_key_exists($itemTransaction->merchantCommissionRateAmount)) {
                $paymentItem->setMerchantCommissionRateAmount($itemTransaction->merchantCommissionRateAmount);
            }
            if (array_key_exists($itemTransaction->iyziCommissionRateAmount)) {
                $paymentItem->setIyziCommissionRateAmount($itemTransaction->iyziCommissionRateAmount);
            }
            if (array_key_exists($itemTransaction->iyziCommissionFee)) {
                $paymentItem->setIyziCommissionFee($itemTransaction->iyziCommissionFee);
            }
            if (array_key_exists($itemTransaction->blockageRate)) {
                $paymentItem->setBlockageRate($itemTransaction->blockageRate);
            }
            if (array_key_exists($itemTransaction->blockageRateAmountMerchant)) {
                $paymentItem->setBlockageRateAmountMerchant($itemTransaction->blockageRateAmountMerchant);
            }
            if (array_key_exists($itemTransaction->blockageRateAmountSubMerchant)) {
                $paymentItem->setBlockageRateAmountSubMerchant($itemTransaction->blockageRateAmountSubMerchant);
            }
            if (array_key_exists($itemTransaction->blockageResolvedDate)) {
                $paymentItem->setBlockageResolvedDate($itemTransaction->blockageResolvedDate);
            }
            if (array_key_exists($itemTransaction->subMerchantKey)) {
                $paymentItem->setSubMerchantKey($itemTransaction->subMerchantKey);
            }
            if (array_key_exists($itemTransaction->subMerchantPrice)) {
                $paymentItem->setSubMerchantPrice($itemTransaction->subMerchantPrice);
            }
            if (array_key_exists($itemTransaction->subMerchantPayoutRate)) {
                $paymentItem->setSubMerchantPayoutRate($itemTransaction->subMerchantPayoutRate);
            }
            if (array_key_exists($itemTransaction->subMerchantPayoutAmount)) {
                $paymentItem->setSubMerchantPayoutAmount($itemTransaction->subMerchantPayoutAmount);
            }
            if (array_key_exists($itemTransaction->merchantPayoutAmount)) {
                $paymentItem->setMerchantPayoutAmount($itemTransaction->merchantPayoutAmount);
            }
            if (array_key_exists($itemTransaction->convertedPayout)) {
                $paymentItem->setConvertedPayout($this->mapConvertedPayout($itemTransaction->convertedPayout));
            }
            $paymentItems[$index] = $paymentItem;
        }
        return $paymentItems;
    }

    private function mapConvertedPayout($payout)
    {
        $convertedPayout = new ConvertedPayout();

        if (array_key_exists($payout->paidPrice)) {
            $convertedPayout->setPaidPrice($payout->paidPrice);
        }
        if (array_key_exists($payout->iyziCommissionRateAmount)) {
            $convertedPayout->setIyziCommissionRateAmount($payout->iyziCommissionRateAmount);
        }
        if (array_key_exists($payout->iyziCommissionFee)) {
            $convertedPayout->setIyziCommissionFee($payout->iyziCommissionFee);
        }
        if (array_key_exists($payout->blockageRateAmountMerchant)) {
            $convertedPayout->setBlockageRateAmountMerchant($payout->blockageRateAmountMerchant);
        }
        if (array_key_exists($payout->blockageRateAmountSubMerchant)) {
            $convertedPayout->setBlockageRateAmountSubMerchant($payout->blockageRateAmountSubMerchant);
        }
        if (array_key_exists($payout->subMerchantPayoutAmount)) {
            $convertedPayout->setSubMerchantPayoutAmount($payout->subMerchantPayoutAmount);
        }
        if (array_key_exists($payout->merchantPayoutAmount)) {
            $convertedPayout->setMerchantPayoutAmount($payout->merchantPayoutAmount);
        }
        if (array_key_exists($payout->iyziConversionRate)) {
            $convertedPayout->setIyziConversionRate($payout->iyziConversionRate);
        }
        if (array_key_exists($payout->iyziConversionRateAmount)) {
            $convertedPayout->setIyziConversionRateAmount($payout->iyziConversionRateAmount);
        }
        if (array_key_exists($payout->currency)) {
            $convertedPayout->setCurrency($payout->currency);
        }
        return $convertedPayout;
    }
}