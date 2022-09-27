<?php

namespace Omnipay\PowerTranz;


use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\PowerTranz\Support\PWTParametersInterface;
use Omnipay\PowerTranz\Support\TransactionCode;


class PWTGateway extends AbstractGateway implements PWTParametersInterface
{

    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        parent::__construct(null, $httpRequest);
    }

    public function getName()
    {
        return Constants::DRIVER_NAME;
    }

    public function getDefaultParameters()
    {
        $config = include 'src/ConfigArray.php';
        if (array_key_exists(Constants::CONFIG_KEY_PWTCUR, $config) && is_array($config[Constants::CONFIG_KEY_PWTCUR])) {
            $config['currency'] = $config[Constants::CONFIG_KEY_PWTCUR][0];
        }

        return $config;
    }


    public function setMerchantResponseURL($url)
    {
        //$this->setReturnUrl($url);
        return $this->setParameter(Constants::CONFIG_KEY_MERCHANT_RESPONSE_URL, $url);
    }


    public function getMerchantResponseURL()
    {
        return $this->getParameter(Constants::CONFIG_KEY_MERCHANT_RESPONSE_URL);
    }


    public function setReturnUrl($url)
    {
        //  $this->setMerchantResponseURL($url);
        return $this->setParameter(Constants::CONFIG_KEY_WEBHOOK_URL, $url);
    }

    public function getReturnUrl()
    {
        //  $this->setMerchantResponseURL($url);
        return $this->getParameter(Constants::CONFIG_KEY_WEBHOOK_URL);
    }


    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function authorize(array $options = []): \Omnipay\Common\Message\AbstractRequest
    {

        return $this->createRequest("\Omnipay\PowerTranz\Message\Authorize3DS", $options);
    }


    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\NotificationInterface
     */
    public function acceptNotification(array $options = [])
    {
        return $this->createRequest("\Omnipay\PowerTranz\Message\AcceptNotification", $options);
    }


    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\NotificationInterface
     */
    public function purchase($options)
    {
        return $this->createRequest("\Omnipay\PowerTranz\Message\Payment3DS", $options);
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

    public function set3DS($value)
    {
        return $this->setParameter(Constants::AUTHORIZE_OPTION_3DS, $value);
    }

    public function get3DS()
    {
        return $this->getParameter(Constants::AUTHORIZE_OPTION_3DS);
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

    public function setPwtCurrencyList($list)
    {
        return $this->setParameter(Constants::CONFIG_KEY_PWTCUR, $list);
    }

    public function getFacCurrencyList()
    {
        return $this->getParameter(Constants::CONFIG_KEY_PWTCUR);
    }


}