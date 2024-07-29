<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class InstallmentDetailsRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return $this->getInstallmentDetailsEndpoint();
    }

    public function getData()
    {
        $this->validate('cardNumber', 'amount');

        return [
            'credit_card' => $this->getCardNumber(),
            'amount' => $this->getAmount(),
            'currency_code' => $this->getCurrency(),
            'merchant_key' => $this->getMerchantKey(),
        ];
    }

    public function sendData($data)
    {
        try {
            $httpResponse = $this->httpClient->request(
                'POST',
                $this->getEndpoint(),
                [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                    'Authorization' => $this->getToken(),
                ],
                json_encode($data)
            );

            $responseBody = (string) $httpResponse->getBody()->getContents();
            $response = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR) ?? [];

            return new InstallmentDetailsResponse($this, $response);
        } catch (\Exception $e) {
            throw new InvalidResponseException(sprintf(
                'Error communicating with payment gateway: %s',
                $e->getMessage()
            ), $e->getCode(), $e);
        }
    }
}
