<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\BkmInitialize;

class BkmInitializeMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new BkmInitializeMapper($rawResult);
    }

    public function mapBkmInitializeFrom(BkmInitialize $initialize, $jsonObject)
    {
        parent::mapResourceFrom($initialize, $jsonObject);

        if (array_key_exists($jsonObject->htmlContent)) {
            $initialize->setHtmlContent(base64_decode($jsonObject->htmlContent));
        }
        if (array_key_exists($jsonObject->token)) {
            $initialize->setToken($jsonObject->token);
        }
        return $initialize;
    }

    public function mapBkmInitialize(BkmInitialize $initialize)
    {
        return $this->mapBkmInitializeFrom($initialize, $this->jsonObject);
    }
}
