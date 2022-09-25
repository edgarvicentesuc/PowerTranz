<?php


use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PowerTranz\Constants;

return [
    /*
     * Use cautiously for testing and debugging only.
     * Caching transaction requests will save credit card information within the XML request.
     */
//    AbstractRequest::PARAM_CACHE_TRANSACTION => false,
//    AbstractRequest::PARAM_CACHE_REQUEST => false,

    /*
     * These options can and should be overridden via the $gateway->set** methods.
     *
     * @example
     * $gateway->setTestMode = true;
     */

    'testMode' => false,
    Constants::AUTHORIZE_OPTION_3DS => true, // Default 3DS transactions
    Constants::CONFIG_KEY_PWTID => '', // First Atlantic Commerce ID
    Constants::CONFIG_KEY_PWTPWD => '', // First Atlantic Commerce Processing Password

    Constants::GATEWAY_ORDER_IDENTIFIER_PREFIX => '', // Prefix for OrderNumber sent to FAC.
    Constants::GATEWAY_ORDER_IDENTIFIER_AUTOGEN => true, // Set to true to have the gateway generate order numbers if none is supplied for a transaction.

    /*
     * Always the same.
     */
    //Constants::CONFIG_KEY_FACAQID => '88804131', // First Atlantic Commerce Acquirer ID

    /**
     * List all authorized currencies.
     * First in list will be default and populated as 'currency' parameter
     *
     * Override currency with
     * $gateway->setCurrency = "XYZ";
     */
    Constants::CONFIG_KEY_PWTCUR => ['320'],
];