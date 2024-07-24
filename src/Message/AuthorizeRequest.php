<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\ResponseInterface;

class AuthorizeRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return $this->getBaseEndpoint() . '/api/token';
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
        $this->validate('appKey', 'appSecret');

        return [
            'app_id' => $this->getAppKey(),
            'app_secret' => $this->getAppSecret(),
        ];
    }

    public function sendData($data): ResponseInterface
    {
        try {
            $httpResponse = $this->httpClient->request(
                'POST',
                $this->getEndpoint(),
                [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json'
                ],
                json_encode($data)
            );

            $responseBody = (string) $httpResponse->getBody()->getContents();
            $response = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR) ?? [];

            return new AuthorizeResponse($this, $response);
        } catch (\Exception $e) {
            throw new InvalidResponseException(sprintf(
                'Error communicating with payment gateway: %s',
                $e->getMessage()
            ), $e->getCode(), $e);
        }
    }
}
