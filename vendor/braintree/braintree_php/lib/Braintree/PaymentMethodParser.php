<?php
namespace Braintree;

/**
 * Braintree PaymentMethodParser module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Manages Braintree PaymentMethodParser
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 *
 */
class PaymentMethodParser
{
    public static function parsePaymentMethod($response)
    {
        if (array_key_exists($response['creditCard'])) {
            return CreditCard::factory($response['creditCard']);
        } else if (array_key_exists($response['paypalAccount'])) {
            return PayPalAccount::factory($response['paypalAccount']);
        } else if (array_key_exists($response['applePayCard'])) {
            return ApplePayCard::factory($response['applePayCard']);
        } else if (array_key_exists($response['androidPayCard'])) {
            return AndroidPayCard::factory($response['androidPayCard']);
        } else if (array_key_exists($response['amexExpressCheckoutCard'])) {
        // NEXT_MAJOR_VERSION remove deprecated amexExpressCheckoutCard
            return AmexExpressCheckoutCard::factory($response['amexExpressCheckoutCard']);
        } else if (array_key_exists($response['usBankAccount'])) {
            return UsBankAccount::factory($response['usBankAccount']);
        } else if (array_key_exists($response['venmoAccount'])) {
            return VenmoAccount::factory($response['venmoAccount']);
        } else if (array_key_exists($response['visaCheckoutCard'])) {
            return VisaCheckoutCard::factory($response['visaCheckoutCard']);
        // NEXT_MAJOR_VERSION remove deprecated masterpassCard
        } else if (array_key_exists($response['masterpassCard'])) {
            return MasterpassCard::factory($response['masterpassCard']);
        } else if (array_key_exists($response['samsungPayCard'])) {
            return SamsungPayCard::factory($response['samsungPayCard']);
        } else if (is_array($response)) {
            return UnknownPaymentMethod::factory($response);
        } else {
            throw new Exception\Unexpected(
                'Expected payment method'
            );
        }
    }
}
