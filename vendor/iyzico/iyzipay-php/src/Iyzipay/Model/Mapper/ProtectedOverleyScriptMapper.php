<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\Model\ProtectedOverleyScript;

class ProtectedOverleyScriptMapper extends IyzipayResourceMapper
{
    public static function create($rawResult = null)
    {
        return new ProtectedOverleyScriptMapper($rawResult);
    }

    public function mapProtectedOverleyScriptFrom(ProtectedOverleyScript $protectedOverleyScript, $jsonObject)
    {
        parent::mapResourceFrom($protectedOverleyScript, $jsonObject);

        if (array_key_exists($jsonObject->protectedShopId)) {
            $protectedOverleyScript->setProtectedShopId($jsonObject->protectedShopId);
        }
        if (array_key_exists($jsonObject->overlayScript)) {
            $protectedOverleyScript->setOverlayScript($jsonObject->overlayScript);
        }
        return $protectedOverleyScript;
    }

    public function mapProtectedOverleyScript(ProtectedOverleyScript $protectedOverleyScript)
    {
        return $this->mapProtectedOverleyScriptFrom($protectedOverleyScript, $this->jsonObject);
    }
}