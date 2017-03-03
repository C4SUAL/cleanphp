<?php

namespace CleanPhp\Invoicer\Persistence\Hydrator;

use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Persistence\Hydrator\Strategy\DateStrategy;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

class InvoiceHydrator implements HydratorInterface
{
    /**
     * @var ClassMethods
     */
    protected $wrappedHydrator;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * InvoiceHydrator constructor.
     * @param HydratorInterface $hydrator
     * @param OrderRepositoryInterface $orderRepository
     */

    public function __construct(
        HydratorInterface $hydrator,
        OrderRepositoryInterface $orderRepository
    )
    {
        $this->wrappedHydrator = $hydrator;
        $this->wrappedHydrator->addStrategy(
            'invoice_date',
            new DateStrategy()
        );
        $this->orderRepository = $orderRepository;
    }

    public function extract($object)
    {
        $data = $this->wrappedHydrator->extract($object);

        if (isset($data['order']) && $data['order'] instanceof Order) {
            $data['order_id'] = $data['order']->getId();
            unset($data['order']);
        }
        return $data;
    }

    /**
     * @param array $data
     * @param Invoice $invoice
     * @return Invoice
     */
    public function hydrate(array $data, $invoice)
    {
        $order = null;

        if (isset($data['order'])) {
            $order = $this->wrappedHydrator->hydrate($data['order'], new Order());
            unset($data['order']);
        }

        if (isset($data['order_id'])) {
            $order = $this->orderRepository->getById($data['order_id']);
        }

        $invoice = $this->wrappedHydrator->hydrate($data, $invoice);

        if ($order) {
            $invoice->setOrder($order);
        }

        return $invoice;
    }
}