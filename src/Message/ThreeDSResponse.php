<?php

namespace Omnipay\PowerTranz\Message;


use Omnipay\Common\Message\AbstractResponse;


class ThreeDSResponse extends AbstractResponse
{

    protected $post;
    protected $JsonDoc;

    public function __construct($post)
    {
        if ($this->isJson($post)) {
            $this->JsonDoc = $post;
        }
    }

    public function getData()
    {
        return $this->JsonDoc;
    }

    public function isSuccessful()
    {
        return ((intval($this->getResponseCode()) === 5) || (intval($this->getResponseCode()) === 2)) ? true : false;
    }

    public function getTransactionType()
    {
        return $this->queryData("TransactionType");
    }

    public function getApproved()
    {
        return $this->queryData("Approved");
    }

    public function getTransactionIdentifier()
    {
        return $this->queryData("TransactionIdentifier");
    }

    public function getTotalAmount()
    {
        return $this->queryData("TotalAmount");
    }

    public function getCurrencyCode()
    {
        return $this->queryData("CurrencyCode");
    }

    public function getCardBrand()
    {
        return $this->queryData("CardBrand");
    }

    public function getIsoResponseCode()
    {
        return $this->queryData("IsoResponseCode");
    }

    public function getResponseMessage()
    {
        return $this->queryData("ResponseMessage");
    }


    public function getResponseCode()
    {
        return $this->queryData("Eci", "ThreeDSecure", "RiskManagement");
    }


    public function getAuthenticationStatus()
    {
        return $this->queryData("AuthenticationStatus", "ThreeDSecure", "RiskManagement");
    }

    public function getCardholderInfo()
    {
        return $this->queryData("CardholderInfo", "ThreeDSecure", "RiskManagement");
    }

    public function getSpiToken()
    {
        return $this->queryData("SpiToken");
    }

    public function getOrderIdentifier()
    {
        return $this->queryData("OrderIdentifier");
    }

    protected function queryData($element, $parent = null, $main = null)
    {
        $json = json_decode($this->JsonDoc);

        if ($main != null && $parent != null && $main != null) {
            return $json->{$main}->{$parent}->{$element};
        } else if ($main == null && $parent != null && $element != null) {
            return $json->{$parent}->{$element};
        } else if ($main == null && $parent == null && $element != null) {
            return $json->{$element};
        }
    }


    public function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

}