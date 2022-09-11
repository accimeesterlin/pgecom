<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\BasicBkmInitialize;

class BasicBkmInitializeMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new BasicBkmInitializeMapper($rawResult);
    }

    public function mapBasicBkmInitializeFrom(BasicBkmInitialize $initialize, $jsonObject)
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

    public function mapBasicBkmInitialize(BasicBkmInitialize $initialize)
    {
        return $this->mapBasicBkmInitializeFrom($initialize, $this->jsonObject);
    }
}