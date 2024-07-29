<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @return RequestInterface|AbstractRequest
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    public function getCode()
    {
        if ($this->isSuccessful()) {
            return $this->data['status'] ?? null;
        }

        return $this->getErrorCode();
    }

    public function getMessage()
    {
        if ($this->isSuccessful()) {
            return $this->data['status_description'] ?? null;
        }

        return $this->getErrorMessage();
    }

    public function isSuccessful()
    {
        return $this->data['status_code'] == 100;
    }

    public function getTransactionReference()
    {
        return $this->data['order_id'] ?? null;
    }

    public function getErrorCode()
    {
        return $this->data['error_code'] ?? null;
    }

    public function getErrorMessage()
    {
        return $this->data['error'] ?? null;
    }

    public function isRedirect()
    {
        return false;
    }
}
