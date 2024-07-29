<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class InstallmentDetailsResponse extends AbstractResponse
{
    /**
     * @return RequestInterface|AbstractRequest
     */
    public function getRequest()
    {
        return parent::getRequest();
    }

    public function getStatusCode()
    {
        return $this->data['status_code'] ?? null;
    }

    public function getMessage()
    {
        return $this->data['status_description'] ?? null;
    }

    public function isSuccessful()
    {
        return $this->getStatusCode() === 100;
    }

    public function getData()
    {
        if ($this->isSuccessful()) {
            return $this->data['data'] ?? [];
        }

        return [];
    }
}
