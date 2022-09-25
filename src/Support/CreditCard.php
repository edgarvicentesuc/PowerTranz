<?php

namespace Omnipay\PowerTranz\Support;

use \Omnipay\Common\CreditCard as OmniPayCreditCard;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\PowerTranz\Exception\InvalidEmailAddress;

class CreditCard extends OmniPayCreditCard
{
    protected $bin;

    public function validate()
    {
        $this->bin = $this->getBin(6);

        parent::validate();

        return $this
            ->validateCard()
            ->validateCVV();
    }

    protected function validateCard()
    {
        $cardNumber = $this->getNumber();

        switch ($this->getBrand()) {
            case self::BRAND_MASTERCARD:
                if (strlen($cardNumber) != 16) throw new InvalidCreditCardException('Card number should be 16 digits!');
                break;

            case self::BRAND_VISA:
                if (strlen($cardNumber) != 16) throw new InvalidCreditCardException('Card number should be 16 digits!');
                break;

            case self::BRAND_AMEX:
                if (strlen($cardNumber) != 15) throw new InvalidCreditCardException('Card number should be 15 digits!');
                break;
        }

        return $this;
    }

    protected function validateCVV()
    {
        $CVV = $this->getCvv();

        switch ($this->getBrand()) {
            case self::BRAND_MASTERCARD:
                if (strlen($CVV) != 3) throw new InvalidCreditCardException('CVV should be 3 digits!');
                break;

            case self::BRAND_VISA:
                if (strlen($CVV) != 3) throw new InvalidCreditCardException('CVV should be 3 digits!');
                break;

            case self::BRAND_AMEX:
                if (strlen($CVV) != 3) throw new InvalidCreditCardException('CVV should be 4 digits!');
                break;
        }

        return $this;
    }

    public function getBillingEmail()
    {
        return $this->getParameter('billingEmail');
    }

    public function setBillingEmail($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) throw new InvalidEmailAddress($value);
        return $this->setParameter('billingEmail', $value);
    }

    public function setBillingCountry($value)
    {
        return $this->setParameter('billingCountry', $value);
    }

    public function getBin($length = 6)
    {
        if (intval($length) > 6 || intval($length) < 4) $length = 6;

        if ($this->bin) return substr($this->bin, 0, $length);

        if ($this->getNumber()) {
            return substr($this->getNumber(), 0, $length);
        }

        return null;
    }
}