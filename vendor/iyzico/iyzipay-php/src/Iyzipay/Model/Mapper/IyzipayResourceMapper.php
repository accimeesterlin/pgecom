<?php

namespace Iyzipay\Model\Mapper;

use Iyzipay\IyzipayResource;
use Iyzipay\JsonBuilder;

class IyzipayResourceMapper
{
    protected $rawResult;
    protected $jsonObject;

    public function __construct($rawResult)
    {
        $this->rawResult = $rawResult;
    }

    public static function create($rawResult = null)
    {
        return new IyzipayResourceMapper($rawResult);
    }

    public function jsonDecode()
    {
        $this->jsonObject = JsonBuilder::jsonDecode($this->rawResult);
        return $this;
    }

    public function mapResourceFrom(IyzipayResource $resource, $jsonObject)
    {
        if (array_key_exists($jsonObject->status)) {
            $resource->setStatus($jsonObject->status);
        }
        if (array_key_exists($jsonObject->conversationId)) {
            $resource->setConversationId($jsonObject->conversationId);
        }
        if (array_key_exists($jsonObject->errorCode)) {
            $resource->setErrorCode($jsonObject->errorCode);
        }
        if (array_key_exists($jsonObject->errorMessage)) {
            $resource->setErrorMessage($jsonObject->errorMessage);
        }
        if (array_key_exists($jsonObject->errorGroup)) {
            $resource->setErrorGroup($jsonObject->errorGroup);
        }
        if (array_key_exists($jsonObject->locale)) {
            $resource->setLocale($jsonObject->locale);
        }
        if (array_key_exists($jsonObject->systemTime)) {
            $resource->setSystemTime($jsonObject->systemTime);
        }
        if (array_key_exists($this->rawResult)) {
            $resource->setRawResult($this->rawResult);
        }
        return $resource;
    }

    public function mapResource(IyzipayResource $resource)
    {
        return $this->mapResourceFrom($resource, $this->jsonObject);
    }
}