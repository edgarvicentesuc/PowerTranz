<?php

namespace Omnipay\PowerTranz\Message;

use Omnipay\PowerTranz\Exception\RequiredMessageFieldEmpty;
use Omnipay\PowerTranz\Support\TransactionCode;


class Authorize extends AbstractRequest
{

    const MESSAGE_PART_TRANSACTION_DETAILS = "TransactionDetails";
    const MESSAGE_PART_SOURCE = "Source";
    const MESSAGE_PART_BILLING_ADDRESS = "BillingAddress";
    const MESSAGE_PART_EXTENDED_DATA = "ExtendedData";
    const MESSAGE_PART_EXTENDED_DATA_THREEDSECURE = "ThreeDSecure";


    const PARAM_TRANSACTION_IDENTIFIER = 'TransactionIdentifier';
    const PARAM_TOTAL_AMOUNT = 'TotalAmount';
    const PARAM_CURRENCY_CODE = 'CurrencyCode';
    const PARAM_THREEDSECURE = "ThreeDSecure";
    const PARAM_ORDER_IDENTIFIER = 'OrderIdentifier';
    const PARAM_ADDRESS_MATCH = 'AddressMatch';


    const PARAM_SOURCE_NUMBER = "CardPan";
    const PARAM_SOURCE_EXPIRY_DATE = "CardExpiration";
    const PARAM_SOURCE_CVV2 = "CardCvv";
    const PARAM_SOURCE_HOLDER_NAME = "CardHolderName";

    const PARAM_BILLING_ADDRESS_FIRSTNAME = "FirstName";
    const PARAM_BILLING_ADDRESS_LASTNAME = "LastName";
    const PARAM_BILLING_ADDRESS_ADDRESS1 = "Line1";
    const PARAM_BILLING_ADDRESS_ADDRESS2 = "Line2";
    const PARAM_BILLING_ADDRESS_CITY = "City";
    const PARAM_BILLING_ADDRESS_ZIP = "PostalCode";
    const PARAM_BILLING_ADDRESS_STATE = "State";
    const PARAM_BILLING_ADDRESS_COUNTRY = "CountryCode";
    const PARAM_BILLING_ADDRESS_TELEPHONE = "PhoneNumber";
    const PARAM_BILLING_ADDRESS_EMAIL = "EmailAddress";

    const PARAM_EXTENDED_DATA_THREEDSECURE = "ThreeDSecure";
    const PARAM_EXTENDED_DATA_THREEDSECURE_WINDOWS_SIZE = "ChallengeWindowSize";
    const PARAM_EXTENDED_DATA_THREEDSECURE_INDICATOR = "ChallengeIndicator";




    protected $TransactionDetails = [];

    public function getData()
    {
        $this->setTransactionDetails();
        $this->setCardDetails();
        $this->setBillingDetails();

        $ThreedSecure = [];
        $ThreedSecure[self::PARAM_EXTENDED_DATA_THREEDSECURE_WINDOWS_SIZE] = 4;
        $ThreedSecure[self::PARAM_EXTENDED_DATA_THREEDSECURE_INDICATOR] = "01";

        $this->data[self::MESSAGE_PART_EXTENDED_DATA][self::PARAM_EXTENDED_DATA_THREEDSECURE] = $ThreedSecure;


        return $this->data;
    }

    protected function setTransactionDetails()
    {
        $this->TransactionDetails[self::PARAM_TRANSACTION_IDENTIFIER] = $this->getTransactionId();
        $this->TransactionDetails[self::PARAM_TOTAL_AMOUNT] = $this->getAmount();
        $this->TransactionDetails[self::PARAM_CURRENCY_CODE] = ($this->getCurrency() == "GTQ") ? "320" : "840";
        $this->TransactionDetails[self::PARAM_THREEDSECURE] = "true";


        $this->TransactionDetails[self::PARAM_ORDER_IDENTIFIER] = $this->getOrderIdentifier();
        $this->TransactionDetails[self::PARAM_ADDRESS_MATCH] = "true";

        $this->validateTransactionDetails();
    }

    protected function validateTransactionDetails()
    {
        $this->data = $this->TransactionDetails;
    }

    protected function setCardDetails()
    {
        $CardDetails = [];
        $CreditCard = $this->getCard();

        $CreditCard->validate();

        $CardDetails[self::PARAM_SOURCE_NUMBER] = $CreditCard->getNumber();
        $CardDetails[self::PARAM_SOURCE_EXPIRY_DATE] = $CreditCard->getExpiryDate("ym");
        $CardDetails[self::PARAM_SOURCE_CVV2] = $CreditCard->getCvv();
        $CardDetails[self::PARAM_SOURCE_HOLDER_NAME] = $CreditCard->getFirstName() . " " . $CreditCard->getLastName();


        $this->data[self::MESSAGE_PART_SOURCE] = $CardDetails;
    }

    protected function setBillingDetails()
    {
        $BillingDetails = [];

        $CreditCard = $this->getCard();

        $BillingDetails[self::PARAM_BILLING_ADDRESS_FIRSTNAME] = $CreditCard->getBillingFirstName();
        $BillingDetails[self::PARAM_BILLING_ADDRESS_LASTNAME] = $CreditCard->getBillingLastName();
        $BillingDetails[self::PARAM_BILLING_ADDRESS_ADDRESS1] = $CreditCard->getBillingAddress1();
        $BillingDetails[self::PARAM_BILLING_ADDRESS_ADDRESS2] = $CreditCard->getBillingAddress2();
        $BillingDetails[self::PARAM_BILLING_ADDRESS_CITY] = $CreditCard->getBillingCity();
        $BillingDetails[self::PARAM_BILLING_ADDRESS_STATE] = $CreditCard->getBillingState();
        $BillingDetails[self::PARAM_BILLING_ADDRESS_ZIP] = $CreditCard->getBillingPostcode();

        $BillingDetails[self::PARAM_BILLING_ADDRESS_COUNTRY] = ($CreditCard->getBillingCountry() == "GTQ") ? "320" : "840";

        $BillingDetails[self::PARAM_BILLING_ADDRESS_EMAIL] = $CreditCard->getEmail();
        $BillingDetails[self::PARAM_BILLING_ADDRESS_TELEPHONE] = $CreditCard->getBillingPhone();

        $this->data[self::MESSAGE_PART_BILLING_ADDRESS] = $BillingDetails;
    }


}