<?php
namespace CleanPhp\Invoicer\Persistence\Hydrator;

use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use Zend\Hydrator\HydratorInterface;

class OrderHydrator implements HydratorInterface
{
    protected $wrappedHydrator;
    protected $customerRepository;

    public function __construct(
        HydratorInterface $wrappedHydrator,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->wrappedHydrator = $wrappedHydrator;
        $this->customerRepository = $customerRepository;
    }

    public function extract($object)
    {
        return $this->wrappedHydrator->extract($object);
    }

    public function hydrate(array $data, $order)
    {
        $this->wrappedHydrator->hydrate($data, $order);

        if (isset($data['customer_id'])) {
            $order->setCustomer(
                $this->customerRepository->getById($data['customer_id'])
            );
        }

        return $order;
    }
}