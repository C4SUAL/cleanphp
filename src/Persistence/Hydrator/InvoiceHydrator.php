<?php

namespace CleanPhp\Invoicer\Persistence\Hydrator;

use CleanPhp\Invoicer\Persistence\Hydrator\Strategy\DateStrategy;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

class InvoiceHydrator implements HydratorInterface
{
    /**
     * @var ClassMethods
     */
    protected $wrappedHydrator;

    public function __construct(HydratorInterface $hydrator)
    {
        $this->wrappedHydrator = $hydrator;
        $this->wrappedHydrator->addStrategy(
            'invoice_date',
            new DateStrategy()
        );
    }

    public function extract($object)
    {
        return $this->wrappedHydrator->extract($object);
    }

    public function hydrate(array $data, $object)
    {
        return $this->wrappedHydrator->hydrate($data, $object);
    }
}