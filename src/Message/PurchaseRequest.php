<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Item;
use Omnipay\Common\Message\ResponseInterface;

class PurchaseRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return $this->getBaseEndpoint() . $this->getModelEndpoint();
    }

    public function getData()
    {
        $this->validate(
            'merchantKey',
            'transactionId',
            'amount',
            'items',
            'cancelUrl',
            'returnUrl',
            'card',
        );

        $this->getCard()->validate();

        $data = [
            'merchant_key' => $this->getMerchantKey(),
            'return_url' => $this->getReturnUrl(),
            'cancel_url' => $this->getCancelUrl(),
            'currency_code' => $this->getCurrency() ?? 'TRY',

            'name' => $this->getCard()->getFirstName(),
            'surname' => $this->getCard()->getLastName(),
            'invoice_id' => $this->getTransactionId(),
            'invoice_description' => $this->getDescription() ?? $this->getTransactionId(),
            'installments_number' => $this->getInstallment(),
            'total' => $this->getAmount(),
            'cc_holder_name' => $this->getCard()->getName(),
            'cc_no' => $this->getCard()->getNumber(),
            'expiry_month' => $this->getCard()->getExpiryDate('m'),
            'expiry_year' => $this->getCard()->getExpiryDate('y'),
            'cvv' => $this->getCard()->getCvv(),
            'hash_key' => $this->createHash(),
            'ip' => $this->getClientIp(),
            'response_method' => 'POST',
            'transaction_type' => 'Auth',
        ];

        $data['items'] = json_encode(array_map(fn (Item $item) => $item->getParameters(), $this->getItems()->all()));

        return $data;
    }

    public function createHash()
    {
        $data = implode('|', [
            $this->getAmount(),
            $this->getInstallment(),
            $this->getCurrency(),
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

    public function sendData($data): ResponseInterface
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
