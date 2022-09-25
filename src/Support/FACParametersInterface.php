<?php

namespace Omnipay\PowerTranz\Support;

interface FACParametersInterface
{
    public function setPWTId($FACID);

    public function getPWTId();

    public function setPWTPwd($PWD);

    public function getPWTPwd();
}