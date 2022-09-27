<?php

namespace Omnipay\PowerTranz\Message;


use Omnipay\PowerTranz\Constants;
use Omnipay\PowerTranz\Exception\GatewayHTTPException;
use Omnipay\PowerTranz\Support\Guid;
use Omnipay\PowerTranz\Support\PWTParametersInterface;
use Omnipay\PowerTranz\Support\TransactionCode;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
    implements PWTParametersInterface
{
    const SIGNATURE_METHOD_SHA1 = 'SHA1';
    const PARAM_CACHE_TRANSACTION = 'cacheTransaction';
    const PARAM_CACHE_REQUEST = 'cacheRequest';

    protected $data = [];
    protected $TransactionCacheDir = 'transactions/';

    protected $PWTServices = [
        "Authorize3DS" => [
            "api" => "auth",
            "response" => "Authorize3DSResponse"
        ],
        "Payment3DS" => [
            "api" => "payment",
            "response" => "Payment3DSResponse"
        ],
    ];


    public function sendData($data)
    {
//        if ($this->getMessageClassName() == "Payment3DS") {
//            $this->data = $data;
//
//            print_r($this->getEndpoint() . $this->PWTServices[$this->getMessageClassName()]["api"] . "<br>");
//            print_r($this->getPWTId() . "<br>");
//            print_r($this->getPWTPwd() . "<br>");
//            print_r(json_encode($this->data) . "<br>");
//            die();
//        }


        $httpResponse = $this->httpClient
            ->request("POST", $this->getEndpoint() . $this->PWTServices[$this->getMessageClassName()]["api"], [
                "Content-Type" => "application/json",
                "Host" => "staging.ptranz.com",
                "Accept" => "application/json",
                "PowerTranz-PowerTranzId" => $this->getPWTId(),
                "PowerTranz-PowerTranzPassword" => $this->getPWTPwd(),
            ], json_encode($this->data));

        if ($this->getMessageClassName() == "Payment3DS") {
            print_r($httpResponse->getBody());
            die();
        }


        switch ($httpResponse->getStatusCode()) {
            case "200":
                $responseContent = $httpResponse->getBody()->getContents();

                print_r($responseContent);

                return $this->response = new $this->PWTServices[$this->getMessageClassName()]["response"]($this, $responseContent);

            default:
                throw new GatewayHTTPException($httpResponse->getReasonPhrase(), $httpResponse->getStatusCode());
        }
    }

    public function getMessageClassName()
    {
        $className = explode("\\", get_called_class());
        return array_pop($className);
    }

    protected function getEndpoint()
    {
        return ($this->getTestMode()) ? Constants::PLATFORM_PWT_UAT : Constants::PLATFORM_PWT_PROD;
    }

    public function setPWTId($PWTID)
    {
        return $this->setParameter(Constants::CONFIG_KEY_PWTID, $PWTID);
    }

    public function getPWTId()
    {
        return $this->getParameter(Constants::CONFIG_KEY_PWTID);
    }

    public function setPWTPwd($PWD)
    {
        return $this->setParameter(Constants::CONFIG_KEY_PWTPWD, $PWD);
    }

    public function getPWTPwd()
    {
        return $this->getParameter(Constants::CONFIG_KEY_PWTPWD);
    }

    public function setPWTCurrencyList($list)
    {
        return $this->setParameter(Constants::CONFIG_KEY_PWTCUR, $list);
    }

    public function getPWTCurrencyList()
    {
        return $this->getParameter(Constants::CONFIG_KEY_PWTCUR);
    }

    public function getAmountForPWT()
    {
        $length = 12;
        $amount = $this->getAmountInteger();

        while (strlen($amount) < $length) {
            $amount = "0" . $amount;
        }

        return $amount;
    }


    public function setCacheTransaction(bool $value)
    {
        return $this->setParameter(AbstractRequest::PARAM_CACHE_TRANSACTION, $value);
    }

    public function getCacheTransaction()
    {
        return $this->getParameter(AbstractRequest::PARAM_CACHE_TRANSACTION);
    }

    public function setCacheRequest(bool $value)
    {
        return $this->setParameter(AbstractRequest::PARAM_CACHE_REQUEST, $value);
    }

    public function getCacheRequest()
    {
        return $this->getParameter(AbstractRequest::PARAM_CACHE_REQUEST);
    }


    protected function createJSONDoc($data)
    {
        return json_encode($data);
    }


    /**
     * Override parent method to ensure returned value is 3 digit string. (Required by FAC).
     * @return string|null
     */
    public function getCurrencyNumeric()
    {
        $currency = parent::getCurrencyNumeric();
        if (is_string($currency) && strlen($currency) == 2) return "0" . $currency;

        return $currency;
    }

    public function getTransactionId()
    {
        $transactionId = parent::getTransactionId();
        $orderIdentifier = parent::getTransactionId();
        $orderNumberPrefix = $this->getOrderNumberPrefix();
//
//        // generate a number random using microtime
        if (empty($transactionId) && $this->getOrderNumberAutoGen() === true) {
            $transactionId = $this->guidv4();
            $orderIdentifier = $transactionId;
        }
//
        //example TICKET-ASA-000000000001
        if (!empty($orderNumberPrefix) && !empty($transactionId))
            $orderIdentifier = $orderNumberPrefix . "-" . $transactionId;


        $this->setTransactionId($transactionId);
        $this->setOrderIdentifier($orderIdentifier);
        $this->setOrderNumberPrefix('');

        return $transactionId;
    }

    public function setOrderIdentifier($value)
    {
        return $this->setParameter(Constants::GATEWAY_ORDER_IDENTIFIER, $value);
    }

    public function getOrderIdentifier()
    {
        return $this->getParameter(Constants::GATEWAY_ORDER_IDENTIFIER);
    }

    public function setOrderNumberPrefix($value)
    {
        return $this->setParameter(Constants::GATEWAY_ORDER_IDENTIFIER_PREFIX, $value);
    }

    public function getOrderNumberPrefix()
    {
        return $this->getParameter(Constants::GATEWAY_ORDER_IDENTIFIER_PREFIX);
    }

    public function setOrderNumberAutoGen($value)
    {
        return $this->setParameter(Constants::GATEWAY_ORDER_IDENTIFIER_AUTOGEN, $value);
    }

    public function getOrderNumberAutoGen()
    {
        return $this->getParameter(Constants::GATEWAY_ORDER_IDENTIFIER_AUTOGEN);
    }

    public function guidv4($data = null)
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

}
