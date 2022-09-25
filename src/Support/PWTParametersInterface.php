<?php

namespace Omnipay\PowerTranz\Support;

interface PWTParametersInterface
{
    public function setPWTId($FACID);

    public function getPWTId();

    public function setPWTPwd($PWD);

    public function getPWTPwd();
}