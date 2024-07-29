<?php

namespace Omnipay\PayBull\Message;

use Exception;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Omnipay;
use Omnipay\PayBull\Gateway;

abstract class AbstractRequest extends BaseAbstractRequest
{
    protected $endpoints = [
        0 => '/api/paySmart2D',
        1 => '/api/paySmart3D',
        2 =>  '/api/paySmart3D',
        4 => '/purchase/link',
        null => '/api/paySmart3D',
    ];

    abstract public function getEndpoint(): string;

    public function getBaseEndpoint(): string
    {
        return $this->getTestMode()
            ? 'https://test.paybull.com/ccpayment'
            : 'https://app.paybull.com/ccpayment';
    }

    public function getInstallmentDetailsEndpoint(): string
    {
        return $this->getBaseEndpoint() . '/api/getpos';
    }

    public function getModelEndpoint(): string
    {
        $model = $this->getParameter('modelEndpoint');

        return $this->endpoints[$model] ?? $this->endpoints[null];
    }

    public function setModelEndpoint($value)
    {
        return $this->setParameter('modelEndpoint', $value);
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchantKey', $value);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchantKey');
    }

    public function setAppKey($value)
    {
        return $this->setParameter('appKey', $value);
    }

    public function getAppKey()
    {
        return $this->getParameter('appKey');
    }

    public function setAppSecret($value)
    {
        return $this->setParameter('appSecret', $value);
    }

    public function getAppSecret()
    {
        return $this->getParameter('appSecret');
    }

    public function getInstallment()
    {
        return $this->getParameter('installment') ?? 1;
    }

    public function setInstallment($value)
    {
        return $this->setParameter('installment', $value);
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    public function getToken()
    {
        // If the request is AuthenticateRequest, then we don't need to get the token
        if (get_class($this) === AuthorizeRequest::class) {
            return null;
        }

        return $this->getParameter('token');
    }

    public function getCurrency()
    {
        return $this->getParameter('currency') ?? 'TRY';
    }

    public function setCardNumber($value)
    {
        return $this->setParameter('cardNumber', $value);
    }

    public function getCardNumber()
    {
        return $this->getParameter('cardNumber');
    }
}
