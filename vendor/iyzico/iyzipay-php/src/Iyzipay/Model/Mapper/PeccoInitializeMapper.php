<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\PeccoInitialize;

class PeccoInitializeMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new PeccoInitializeMapper($rawResult);
    }

    public function mapPeccoInitializeFrom(PeccoInitialize $peccoInitialize, $jsonObject)
    {
        parent::mapResourceFrom($peccoInitialize, $jsonObject);

        if (array_key_exists($jsonObject->token)) {
            $peccoInitialize->setToken($jsonObject->token);
        }
        if (array_key_exists($jsonObject->htmlContent)) {
            $peccoInitialize->setHtmlContent($jsonObject->htmlContent);
        }
        if (array_key_exists($jsonObject->tokenExpireTime)) {
            $peccoInitialize->setTokenExpireTime($jsonObject->tokenExpireTime);
        }
        if (array_key_exists($jsonObject->redirectUrl)) {
            $peccoInitialize->setRedirectUrl($jsonObject->redirectUrl);
        }
        return $peccoInitialize;
    }

    public function mapPeccoInitialize(PeccoInitialize $peccoInitialize)
    {
        return $this->mapPeccoInitializeFrom($peccoInitialize, $this->jsonObject);
    }
}