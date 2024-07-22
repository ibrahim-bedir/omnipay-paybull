<?php

namespace Omnipay\PayBull\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function getRedirectUrl()
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($this->getData());

        $getMetaRefresh = $dom->getElementsByTagName('meta')->item(1)->attributes->getNamedItem('content')->nodeValue;
        $getMetaRefresh = explode('url=', $getMetaRefresh);
        $redirectUrl = trim($getMetaRefresh[1], "'");

        return $redirectUrl;
    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->data;
    }
}
