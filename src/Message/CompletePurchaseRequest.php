<?php

namespace Omnipay\PayBull\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getEndpoint(): string
    {
        return $this->getBaseEndpoint() . $this->getModelEndpoint();
    }

    public function getData()
    {
        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
