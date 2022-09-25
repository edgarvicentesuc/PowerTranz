<?php

namespace Omnipay\PowerTranz\Message;


use Omnipay\PowerTranz\Constants;
use Omnipay\PowerTranz\Exception\GatewayHTTPException;
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

    protected $FACServices = [
        "Authorize" => [
            "request" => "auth",
            "response" => "AuthorizeResponse"
        ],
        "TransactionStatus" => [
            "request" => "TransactionStatusRequest",
            "response" => "TransactionStatusResponse"
        ],
        "TransactionModification" => [
            "request" => "TransactionModificationRequest",
            "response" => "TransactionModificationResponse"
        ],
        "Tokenize" => [
            "request" => "TokenizeRequest",
            "response" => "TokenizeResponse"
        ],
        "Authorize3DS" => [
            "request" => "auth",
            "response" => "Authorize3DSResponse"
        ],
        "HostedPagePreprocess" => [
            "request" => "HostedPagePreprocessRequest",
            "response" => "HostedPagePreprocessResponse"
        ],
        "HostedPageResults" => [
            "request" => "string",
            "response" => "HostedPageResultsResponse"
        ]
    ];


    public function sendData($data)
    {
        //  $this->createNewXMLDoc($data);

        print_r("<pre>");
        print_r( "desde send data");
        print_r("</pre>");
//

        print_r("<pre>");
        print_r( $this->data);
        print_r("</pre>");
//
//        $httpResponse = $this->httpClient
//            ->request("POST", $this->getEndpoint() . $this->getMessageClassName(), [
//                "Content-Type" => "application/json",
//                "Host" => "staging.ptranz.com",
//                "Accept" => "YG4Cy18lgTMW5GjRq9NztMAygPVKgnGzYeWv6SKXISiz7Zq8uqMh2l1",
//                "PowerTranz-PowerTranzId" => $this->getPWTId(),
//                "PowerTranz-PowerTranzPassword" => $this->getPWTPwd(),
//            ], $this->data);
//
//
//        switch ($httpResponse->getStatusCode()) {
//            case "200":
//                $responseContent = $httpResponse->getBody()->getContents();
//                //  $responseClassName = __NAMESPACE__ . "\\" . $this->FACServices[$this->getMessageClassName()]["response"];
//
////                $responseXML = new \SimpleXMLElement($responseContent);
////                $responseXML->registerXPathNamespace("fac", Constants::PLATFORM_XML_NS);
//
////                if ($this->getCacheTransaction()) {
////                    if (!is_dir($this->TransactionCacheDir)) {
////                        $cacheDirExists = mkdir($this->TransactionCacheDir);
////                    } else {
////                        $cacheDirExists = true;
////                    }
////
////                    if ($cacheDirExists)
////                        $responseXML->asXML($this->TransactionCacheDir . $this->getMessageClassName() . 'Response_' . $this->getTransactionId() . '.xml');
////                }
//
//                return $responseContent;
//
//            default:
//                throw new GatewayHTTPException($httpResponse->getReasonPhrase(), $httpResponse->getStatusCode());
//        }
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
        $orderNumberPrefix = $this->getOrderNumberPrefix();

        // generate a number random using microtime
        if (empty($transactionId) && $this->getOrderNumberAutoGen() === true) {
            $transactionId = microtime(true);
        }

        //example TICKET-ASA-000000000001
        if (!empty($orderNumberPrefix) && !empty($transactionId))
            $transactionId = $orderNumberPrefix . "-" . $transactionId;

        $this->setTransactionId($transactionId);
        $this->setOrderNumberPrefix('');

        return $transactionId;
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


}
