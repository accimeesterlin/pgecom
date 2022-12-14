<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message;

use Omnipay\Common\Message\RequestInterface;

final class RefundResponse extends AbstractResponse
{
    /** @var int */
    private $statusCode;

    /**
     * @param RequestInterface $request
     * @param mixed            $data
     * @param int              $statusCode
     */
    public function __construct(RequestInterface $request, $data, $statusCode)
    {
        parent::__construct($request, $data);

        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function isSuccessful(): bool
    {
        return parent::isSuccessful() && 201 === $this->statusCode;
    }
}
