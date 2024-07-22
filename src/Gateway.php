<?php

namespace Omnipay\PayBull;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PayBull\Message\AuthenticateRequest;
use Omnipay\PayBull\Message\AuthenticateResponse;
use Omnipay\PayBull\Message\CompletePurchaseRequest;
use Omnipay\PayBull\Message\DetailsRequest;
use Omnipay\PayBull\Message\DetailsResponse;
use Omnipay\PayBull\Message\ParametersTrait;
use Omnipay\PayBull\Message\PurchaseRequest;

class Gateway extends AbstractGateway
{
    use ParametersTrait;

    public function getName()
    {
        return 'PayBull';
    }

    /**
     * @return PurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        /** @var AuthenticateResponse $authResponse */
        $authResponse = $this->authenticate()->send();

        if (! $authResponse->isSuccessful()) {
            throw new \Exception($authResponse->getMessage());
        }

        $parameters['access_token'] = $authResponse->getAccessToken();

        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * @return CompletePurchaseRequest|\Omnipay\Common\Message\AbstractRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }

    /**
     * @return \Omnipay\Common\Message\AbstractRequest|DetailsResponse
     */
    public function details(array $parameters = [])
    {
        if (! isset($parameters['access_token'])) {
            $authentication = $this->authenticate($parameters)->send();
            $parameters['access_token'] = $authentication->getAccessToken();
        }

        return $this->createRequest(DetailsRequest::class, $parameters);
    }

    /**
     * @return AuthenticateRequest|AbstractRequest
     */
    public function authenticate(array $parameters = [])
    {
        return $this->createRequest(AuthenticateRequest::class, $parameters);
    }
}
