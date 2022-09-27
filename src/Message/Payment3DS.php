<?php


namespace Omnipay\PowerTranz\Message;

use Omnipay\PowerTranz\Exception\RequiredMessageFieldEmpty;
use Omnipay\PowerTranz\Support\TransactionCode;


class Payment3DS extends AbstractRequest
{

    public function send()
    {
        if ($this->isJson($_POST)) {
            $this->JsonDoc = $_POST;
            return $this->sendData($this->getSpiToken());
        }
    }

    public function getSpiToken()
    {
        return $this->queryData("SpiToken");
    }

//    public function sendData($data)
//    {
//        print_r($data);
//        //return new ThreeDSResponse($data['Response']);
//    }


    public function getData()
    {
        return $this->getParameters();
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