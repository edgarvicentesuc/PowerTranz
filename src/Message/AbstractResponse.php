<?php

namespace Omnipay\PowerTranz\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;
use Omnipay\PowerTranz\Exception\InvalidResponseData;
use Omnipay\PowerTranz\Constants;

abstract class AbstractResponse extends OmnipayAbstractResponse
{
    const AUTHORIZE_CREDIT_CARD_TRANSACTION_RESULTS = "CreditCardTransactionResults";
    const AUTHORIZE_BILLING_DETAILS = "BillingDetails";
    const AUTHORIZE_FRAUD_CONTROL_RESULTS = "FraudControlResults";

    public function __construct(RequestInterface $request, $data)
    {
        print_r(json_encode($data));

        if ($this->isJson($data)) {
            $this->request = $request;
            $this->data = $data;

            parent::__construct($request, $data);

//            if (intval($this->queryData("ResponseCode")) === 1)
//                $this->verifySignature();

        } else {
            throw new InvalidResponseData("Response data is not JSON VALID");
        }
    }

    public function getRequest(): AbstractRequest
    {
        return $this->request;
    }

    public function getData()
    {
        return $this->data;
    }

    protected function queryData($element, $parent = null)
    {
        $json = json_decode($this->data);
        return $json[$element];
    }

    public function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    abstract public function verifySignature();
}