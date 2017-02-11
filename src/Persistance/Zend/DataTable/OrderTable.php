<?php

namespace CleanPhp\Invoicer\Persistence\Zend\TableGateway;

use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;

class OrderTable extends AbstractDataTable implements OrderRepositoryInterface
{
    public function getUninvoicedOrders()
    {
        // TODO: Implement getUninvoicedOrders() method.
    }

}
