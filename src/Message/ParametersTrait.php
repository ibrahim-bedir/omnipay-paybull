<?php

namespace Omnipay\PayBull\Message;

trait ParametersTrait
{
    public function getMerchantKey()
    {
        return $this->getParameter('merchant_key');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchant_key', $value);
    }

    public function getAppId()
    {
        return $this->getParameter('app_id');
    }

    public function setAppId($value)
    {
        return $this->setParameter('app_id', $value);
    }

    public function getAppSecret()
    {
        return $this->getParameter('app_secret');
    }

    public function setAppSecret($value)
    {
        return $this->setParameter('app_secret', $value);
    }

    public function setFirstName($value)
    {
        return $this->setParameter('first_name', $value);
    }

    public function getFirstName()
    {
        return $this->getParameter('first_name');
    }

    public function setLastName($value)
    {
        return $this->setParameter('last_name', $value);
    }

    public function getLastName()
    {
        return $this->getParameter('last_name');
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('cancel_url', $value);
    }

    public function getCancelUrl()
    {
        return $this->getParameter('cancel_url');
    }

    public function setReturnUrl($value)
    {
        return $this->setParameter('return_url', $value);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('return_url');
    }
}
