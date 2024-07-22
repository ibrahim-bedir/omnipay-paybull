<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Item;

class PurchaseRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return $this->getTestMode()
            ? 'https://test.paybull.com/ccpayment/api/paySmart3D'
            : 'https://app.paybull.com/ccpayment/api/paySmart3D';
    }

    public function getAccessToken(): string
    {
        return $this->getParameter('access_token');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('access_token', $value);
    }

    public function getCurrencyCode()
    {
        return $this->getCurrency() ?? 'TRY';
    }

    public function getInstallmentsNumber()
    {
        return $this->getInstallments() ?? 1;
    }

    public function getData()
    {
        $this->validate(
            'merchant_key',
            'transaction_id',
            'amount',
            'first_name',
            'last_name',
            'items',
            'cancel_url',
            'return_url',
            'card',
        );

        $this->getCard()->validate();

        $data = [
            'merchant_key' => $this->getMerchantKey(),
            'return_url' => $this->getReturnUrl(),
            'cancel_url' => $this->getCancelUrl(),
            'currency_code' => $this->getCurrencyCode(),

            'name' => $this->getFirstName(),
            'surname' => $this->getLastName(),
            'invoice_id' => $this->getTransactionId(),
            'invoice_description' => $this->getDescription() ?? $this->getTransactionId(),
            'installments_number' => $this->getInstallmentsNumber(),
            'total' => $this->getAmount(),
            'cc_holder_name' => $this->getCard()->getName(),
            'cc_no' => $this->getCard()->getNumber(),
            'expiry_month' => $this->getCard()->getExpiryDate('m'),
            'expiry_year' => $this->getCard()->getExpiryDate('y'),
            'cvv' => $this->getCard()->getCvv(),
            'hash_key' => $this->createHash(),
        ];

        $data['items'] = collect($this->getItems())->map(function (Item $item) {
            return [
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'quantity' => $item->getQuantity(),
                'description' => $item->getDescription(),
            ];
        })->toJson();

        return $data;
    }

    public function createHash()
    {
        $this->validate('amount', 'installments', 'currency', 'merchant_key', 'transaction_id');

        $data = implode('|', [
            $this->getAmount(),
            $this->getInstallmentsNumber(),
            $this->getCurrencyCode(),
            $this->getMerchantKey(),
            $this->getTransactionId(),
        ]);

        $iv = substr(sha1(mt_rand()), 0, 16);
        $password = sha1($this->getAppSecret());

        $salt = substr(sha1(mt_rand()), 0, 4);
        $saltWithPassword = hash('sha256', $password.$salt);

        $encrypted = openssl_encrypt("$data", 'aes-256-cbc', "$saltWithPassword", 0, $iv);

        $msg_encrypted_bundle = "$iv:$salt:$encrypted";

        return str_replace('/', '__', $msg_encrypted_bundle);
    }

    public function sendData($data)
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->getEndpoint(),
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->getAccessToken(),
            ],
            json_encode($data)
        );

        $responseBody = $httpResponse->getBody()->getContents();

        return $this->response = new PurchaseResponse($this, $responseBody);
    }
}
