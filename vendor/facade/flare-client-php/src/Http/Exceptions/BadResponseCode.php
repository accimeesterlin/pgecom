<?php

namespace Facade\FlareClient\Http\Exceptions;

use Exception;
use Facade\FlareClient\Http\Response;

class BadResponseCode extends Exception
{
    /** @var \Facade\FlareClient\Http\Response */
    public $response;

    /** @var array */
    public $errors;

    public static function createForResponse(Response $response)
    {
        $exception = new static(static::getMessageForResponse($response));

        $exception->response = $response;

        $bodyErrors = array_key_exists($response->getBody()['errors']) ? $response->getBody()['errors'] : [];

        $exception->errors = $bodyErrors;

        return $exception;
    }

    public static function getMessageForResponse(Response $response)
    {
        return "Response code {$response->getHttpResponseCode()} returned";
    }
}
