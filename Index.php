<?php

namespace Omnipay\PowerTranz;


use Omnipay\Omnipay;

$gateway = Omnipay::create('PowerTranz');

$gateway->setTestMode("PowerTranz")
    ->setIntegrationOption("")
    ->setPWTID('')
    ->setPWTPwd('')
    ->set3DS(true);
