<?php

namespace Omnipay\PayBull;

use Exception;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PayBull\Message\DetailsRequest;
use Omnipay\PayBull\Message\DetailsResponse;
use Omnipay\PayBull\Message\PurchaseRequest;
use Omnipay\PayBull\Message\AuthorizeRequest;
use Omnipay\PayBull\Message\AuthorizeResponse;
use Omnipay\PayBull\Message\CompletePurchaseRequest;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayBull';
    }

    /**
     * @return AuthorizeRequest|AbstractRequest
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    /**
     * @return PurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        $this->getToken();

        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @return CompletePurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function completePurchase(array $parameters = [])
    {
        $this->getToken();

        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * @return \Omnipay\Common\Message\AbstractRequest|DetailsResponse
     */
    public function details(array $parameters = [])
    {
        $this->getToken();

        return $this->createRequest(DetailsRequest::class, $parameters);
    }

    private function getToken()
    {
        /** @var AuthorizeResponse $response */
        $response = $this->authorize()->send();
        $token = $response->getToken();

        if($response->isSuccessful() === false || empty($token)) {
            throw new Exception('Failed to get token');
        }

        $this->setParameter('token', $token);
        $this->setParameter('modelEndpoint', $response->getModel());
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
}
