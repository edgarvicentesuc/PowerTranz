<?php

namespace Omnipay\PowerTranz\Message;


use Omnipay\Common\Message\RedirectResponseInterface;

class Payment3DSResponse extends AbstractResponse implements RedirectResponseInterface
{

    protected $post;
//    protected $JsonDoc;

//    public function __construct($post)
//    {
//        if ($this->isJson($post)) {
//            $this->JsonDoc = $post;
//        }
//    }

    public function isSuccessful()
    {
        return false;
    }

//    public function getData()
//    {
//        return $this->JsonDoc;
//    }

    public function verifySignature()
    {
        // TODO: Implement verifySignature() method.
    }

//    protected function queryData($element, $parent = null, $main = null)
//    {
//        $json = json_decode($this->JsonDoc);
//
//        if ($main != null && $parent != null && $main != null) {
//            return $json->{$main}->{$parent}->{$element};
//        } else if ($main == null && $parent != null && $element != null) {
//            return $json->{$parent}->{$element};
//        } else if ($main == null && $parent == null && $element != null) {
//            return $json->{$element};
//        }
//    }
//
//
//    public function isJson($string)
//    {
//        json_decode($string);
//        return json_last_error() === JSON_ERROR_NONE;
//    }

}