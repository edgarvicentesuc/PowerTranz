<?php

namespace Omnipay\PowerTranz\Message;


class Authorize3DS extends Authorize
{

    const PARAM_EXTENDED_DATA_MERCHANT_URL = "merchantResponseURL";
    const PARAM_WEBHOOK_URL = "returnURL";

    public function getData()
    {
        parent::getData();
        $this->applyMerchantResponseURL();

        return $this->data;
    }

    public function setReturnUrl($url)
    {
        $this->setParameter(self::PARAM_WEBHOOK_URL, $url);
    }

    public function getReturnUrl()
    {
        return $this->getParameter(self::PARAM_WEBHOOK_URL);
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
        $this->data[self::MESSAGE_PART_EXTENDED_DATA][ucfirst(self::PARAM_EXTENDED_DATA_MERCHANT_URL)] = $this->getMerchantResponseURL();
    }

}