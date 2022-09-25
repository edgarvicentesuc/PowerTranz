<?php

namespace Omnipay\PowerTranz;

class Constants
{

    const DRIVER_NAME = "PowerTranz - Payment Gateway";
    const PLATFORM_PWT_UAT = 'https://staging.ptranz.com/api/spi/';
    const PLATFORM_PWT_PROD = 'https://tbd.ptranz.com/api/spi/';

    const CONFIG_KEY_PWTID = 'PWTId';
    const CONFIG_KEY_PWTPWD = 'PWTpwd';
    const CONFIG_KEY_MERCHANT_RESPONSE_URL = 'merchantResponseURL';

    const AUTHORIZE_OPTION_3DS = 'ThreeDSecure';
    const GATEWAY_ORDER_IDENTIFIER_PREFIX = 'orderNumberPrefix';
    const GATEWAY_ORDER_IDENTIFIER_AUTOGEN = 'orderNumberAutoGen';
    const GATEWAY_ORDER_IDENTIFIER = 'orderIdentifier';

    const CONFIG_KEY_PWTCUR = 'facCurrencyList';

    const CONFIG_BILLING_STATE_CODE = 'GT';
    const CONFIG_COUNTRY_CURRENCY_CODE = '320';
}