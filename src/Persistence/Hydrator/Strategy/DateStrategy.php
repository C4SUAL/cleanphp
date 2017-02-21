<?php

namespace CleanPhp\Invoicer\Persistence\Hydrator\Strategy;

use Zend\Hydrator\Strategy\DefaultStrategy;

class DateStrategy extends DefaultStrategy
{
    public function hydrate($value)
    {
        if (is_string($value)) {
            $date = new \DateTime($value);
        }
        return $date;
    }

    public function extract($value)
    {
        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d');
        }
        return $value;
    }
}