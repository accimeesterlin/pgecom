<?php

namespace Razorpay\Api;

use Countable;

class Collection extends Entity implements Countable
{
    public function count():int
    {
        $count = 0;

        if (array_key_exists($this->attributes['count']))
        {
            return $this->attributes['count'];
        }

        return $count;
    }
}
