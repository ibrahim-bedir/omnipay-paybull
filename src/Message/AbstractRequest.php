<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    use ParametersTrait;

    public function getTransactionId()
    {
        return $this->getParameter('transaction_id');
    }

    public function setTransactionId($value)
    {
        return $this->setParameter('transaction_id', $value);
    }

    public function setInstallments($value)
    {
        return $this->setParameter('installments', $value);
    }

    public function getInstallments()
    {
        return $this->getParameter('installments');
    }
}
