<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\RequestInterface;

class AuthenticateResponse extends \Omnipay\Common\Message\AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        if (! $this->isSuccessful()) {
            throw new InvalidResponseException(sprintf(
                'Failed to acquire an access token. Error Code: "%s" Error Message: "%s"',
                $this->getStatusCode(),
                $this->getMessage()
            ), $this->getStatusCode());
        }
    }

    public function getAccessToken(): ?string
    {
        return $this->data['data']['token'] ?? null;
    }

    public function getStatusCode(): ?int
    {
        if (empty($this->data)) {
            return 'Empty response message';
        }

        return $this->data['status_code'] ?? null;
    }

    public function getMessage(): string
    {
        if (empty($this->data)) {
            return 'Empty response message';
        }

        return $this->data['status_description'];
    }

    /**
     * Is the response successful?
     */
    public function isSuccessful(): bool
    {
        $statusCode = $this->data['status_code'] ?? null;
        $statusDescription = $this->data['status_description'] ?? null;

        return $statusCode != 30 || $statusDescription != 'Invalid credentials';
    }
}
