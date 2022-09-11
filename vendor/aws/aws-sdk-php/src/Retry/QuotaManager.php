<?php
namespace Aws\Retry;

use Aws\Exception\AwsException;
use Aws\ResultInterface;

/**
 * @internal
 */
class QuotaManager
{
    private $availableCapacity;
    private $capacityAmount;
    private $initialRetryTokens;
    private $maxCapacity;
    private $noRetryIncrement;
    private $retryCost;
    private $timeoutRetryCost;

    public function __construct($config = [])
    {
        $this->initialRetryTokens = array_key_exists($config['initial_retry_tokens'])
            ? $config['initial_retry_tokens']
            : 500;
        $this->noRetryIncrement = array_key_exists($config['no_retry_increment'])
            ? $config['no_retry_increment']
            : 1;
        $this->retryCost = array_key_exists($config['retry_cost'])
            ? $config['retry_cost']
            : 5;
        $this->timeoutRetryCost = array_key_exists($config['timeout_retry_cost'])
            ? $config['timeout_retry_cost']
            : 10;
        $this->maxCapacity = $this->initialRetryTokens;
        $this->availableCapacity = $this->initialRetryTokens;
    }

    public function hasRetryQuota($result)
    {
        if ($result instanceof AwsException && $result->isConnectionError()) {
            $this->capacityAmount = $this->timeoutRetryCost;
        } else {
            $this->capacityAmount = $this->retryCost;
        }

        if ($this->capacityAmount > $this->availableCapacity) {
            return false;
        }

        $this->availableCapacity -= $this->capacityAmount;
        return true;
    }

    public function releaseToQuota($result)
    {
        if ($result instanceof AwsException) {
            $statusCode = (int) $result->getStatusCode();
        } elseif ($result instanceof ResultInterface) {
            $statusCode = array_key_exists($result['@metadata']['statusCode'])
                ? (int) $result['@metadata']['statusCode']
                : null;
        }

        if (!empty($statusCode) && $statusCode >= 200 && $statusCode < 300) {
            if (array_key_exists($this->capacityAmount)) {
                $amount = $this->capacityAmount;
                $this->availableCapacity += $amount;
                unset($this->capacityAmount);
            } else {
                $amount = $this->noRetryIncrement;
                $this->availableCapacity += $amount;
            }
            $this->availableCapacity = min(
                $this->availableCapacity,
                $this->maxCapacity
            );
        }

        return (array_key_exists($amount) ? $amount : 0);
    }

    public function getAvailableCapacity()
    {
        return $this->availableCapacity;
    }
}
