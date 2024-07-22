<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;

class AuthenticateRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return $this->getTestMode()
            ? 'https://test.paybull.com/ccpayment/api/token'
            : 'https://app.paybull.com/ccpayment/api/token';
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('app_id', 'app_secret');

        return [
            'app_id' => $this->getAppId(),
            'app_secret' => $this->getAppSecret(),
        ];
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed  $data The data to send
     * @return ResponseInterface|AuthenticateResponse
     *
     * @throws InvalidResponseException
     */
    public function sendData($data): ResponseInterface
    {
        try {
            $httpResponse = $this->httpClient->request(
                'POST',
                $this->getEndpoint(),
                [
                    'Content-type' => 'application/json',
                ],
                json_encode($data)
            );

            $responseBody = $httpResponse->getBody()->getContents();
            $response = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR) ?? [];

            return new AuthenticateResponse($this, $response);
        } catch (\Exception $e) {
            throw new InvalidResponseException(sprintf(
                'Error communicating with payment gateway: %s',
                $e->getMessage()
            ), $e->getCode(), $e);
        }
    }

    /**
     * @return ResponseInterface|AuthenticateResponse
     */
    public function send()
    {
        return parent::send();
    }
}
