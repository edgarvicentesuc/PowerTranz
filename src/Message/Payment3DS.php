<?php


namespace Omnipay\PowerTranz\Message;

use Omnipay\PowerTranz\Exception\RequiredMessageFieldEmpty;
use Omnipay\PowerTranz\Support\TransactionCode;


class Payment3DS extends AbstractRequest
{

    public function send()
    {
        return $this->sendData($this->data);
    }

    public function sendData($data)
    {
        print_r($data);
        //return new ThreeDSResponse($data['Response']);
    }


    public function getData()
    {
        return $this->getParameters();
    }

}