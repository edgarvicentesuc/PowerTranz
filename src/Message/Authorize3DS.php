<?php

namespace Omnipay\PowerTranz\Message;


class Authorize3DS extends Authorize
{

    const PARAM_EXTENDED_DATA_MERCHANT_URL = "merchantResponseURL";

    public function getData()
    {
        parent::getData();
        $this->applyMerchantResponseURL();

        return $this->data;
    }

    public function setReturnUrl($url)
    {
        $this->setParameter("returnUrl", $url);
        return $this->setMerchantResponseURL($url);
    }

    public function getReturnUrl()
    {
        return $this->getMerchantResponseURL();
    }

    public function setMerchantResponseURL($url)
    {
        return $this->setParameter(self::PARAM_EXTENDED_DATA_MERCHANT_URL, $url);
    }

    public function getMerchantResponseURL()
    {
        return $this->getParameter(self::PARAM_EXTENDED_DATA_MERCHANT_URL);
    }

    protected function applyMerchantResponseURL()
    {
        $this->data[ucfirst(self::PARAM_EXTENDED_DATA_MERCHANT_URL)] = $this->getMerchantResponseURL();
    }

}