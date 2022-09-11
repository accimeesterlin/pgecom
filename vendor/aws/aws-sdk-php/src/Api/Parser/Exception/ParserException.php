<?php
namespace Aws\Api\Parser\Exception;

use Aws\HasMonitoringEventsTrait;
use Aws\MonitoringEventsInterface;
use Aws\ResponseContainerInterface;
use Psr\Http\Message\ResponseInterface;

class ParserException extends \RuntimeException implements
    MonitoringEventsInterface,
    ResponseContainerInterface
{
    use HasMonitoringEventsTrait;

    private $errorCode;
    private $requestId;
    private $response;

    public function __construct($message = '', $code = 0, $previous = null, array $context = [])
    {
        $this->errorCode = array_key_exists($context['error_code']) ? $context['error_code'] : null;
        $this->requestId = array_key_exists($context['request_id']) ? $context['request_id'] : null;
        $this->response = array_key_exists($context['response']) ? $context['response'] : null;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the error code, if any.
     *
     * @return string|null
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Get the request ID, if any.
     *
     * @return string|null
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * Get the received HTTP response if any.
     *
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}
