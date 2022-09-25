<?php

namespace Omnipay\PowerTranz;


use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\PowerTranz\Support\FACParametersInterface;
use Omnipay\PowerTranz\Support\TransactionCode;


class PWTGateway extends AbstractGateway implements FACParametersInterface
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


    /**
     * Alias for setReturnUrl($url)
     * @param string $url
     * @return \Omnipay\Powertranz\PWTGateway
     * @see \Omnipay\Powertranz\FACGateway::setReturnUrl();
     *
     */
    public function setMerchantResponseURL($url)
    {
        $this->setReturnUrl($url);
        return $this->setParameter(Constants::CONFIG_KEY_MERCHANT_RESPONSE_URL, $url);
    }

    /**
     *
     * @return string | NULL
     */
    public function getMerchantResponseURL()
    {
        return $this->getParameter(Constants::CONFIG_KEY_MERCHANT_RESPONSE_URL);
    }

    /**
     * returnUrl will be used to capture the 3DS transaction response.
     * It will also configure the MerchantResponseURL option of the gateway which is required by FAC.
     * MerchantResponseURL can be set directly using setMerchantResponseURL($url), but using setReturnUrl($url) is preferred to maintain compatibility with Omnipay.
     *
     * @param string $url
     * @return \Omnipay\Powertranz\PWTGateway
     */
    public function setReturnUrl($url)
    {
        $this->setMerchantResponseURL($url);
        return $this->setParameter("returnUrl", $url);
    }


    /**
     * Authorize only transaction.
     *
     * {@inheritDoc}
     * @see \Omnipay\Common\GatewayInterface::authorize($options)
     */
    public function authorize(array $options = []): \Omnipay\Common\Message\AbstractRequest
    {
        // Additional transaction codes for AVS checks etc. (if required) can be set when configuring the gateway
        // Default Transaction Code is 0
        if (!array_key_exists('TransactionIdentifier', $options)) {
            $options['TransactionIdentifier'] = new TransactionCode([TransactionCode::NONE]);
        }

        // Non-3DS transactions.
        return $this->createRequest("\Omnipay\FirstAtlanticCommerce\Message\Authorize3DS", $options);
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

    /**
     *
     * @return string | NULL
     */
    public function getReturnUrl()
    {
        return $this->getParameter(Constants::CONFIG_KEY_MERCHANT_RESPONSE_URL);
    }


}