<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
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

    public function getPaymentStatus()
    {
        return $this->data['payment_status'] ?? null;
    }

    public function isSuccessful()
    {
        return $this->getPaymentStatus() === 1;
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
        return $this->getPaymentStatus() === null;
    }

    public function getRedirectUrl()
    {
        return $this->request->getEndpoint();
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->data;
    }

    public function redirect()
    {
        return $this->getRedirectResponse()->getContent();
    }
}
