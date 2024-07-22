<?php

namespace Omnipay\PayBull\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('secret');

        return $this->httpRequest->request->all();
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
