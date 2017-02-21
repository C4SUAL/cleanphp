<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Persistence\Hydrator\OrderHydrator;
use CleanPhp\Invoicer\Persistence\Zend\DataTable\CustomerTable;
use CleanPhp\Invoicer\Persistence\Zend\DataTable\InvoiceTable;
use CleanPhp\Invoicer\Persistence\Zend\DataTable\OrderTable;
use CleanPhp\Invoicer\Persistence\Zend\TableGateway\TableGatewayFactory;
use Zend\Hydrator\ClassMethods;

return [
    'service_manager' => [
        'factories' => [
            \Zend\Db\Adapter\Adapter::class => Zend\Db\Adapter\AdapterServiceFactory::class,
            CustomerTable::class => function ($sm) {
                $factory = new TableGatewayFactory();
                $hydrator = new ClassMethods();
                return new CustomerTable(
                    $factory->createGateway(
                        $sm->get(\Zend\Db\Adapter\Adapter::class),
                        $hydrator,
                        new Customer(),
                        'customers'
                    ),
                    $hydrator
                );
            },
            OrderTable::class => function ($sm) {
                $factory = new TableGatewayFactory();
                $hydrator = $sm->get(OrderHydrator::class);
                return new OrderTable(
                    $factory->createGateway(
                        $sm->get(\Zend\Db\Adapter\Adapter::class),
                        $hydrator,
                        new Order(),
                        'orders'
                    ),
                    $hydrator
                );
            },
            InvoiceTable::class => function ($sm) {
                $factory = new TableGatewayFactory();
                $hydrator = new ClassMethods();
                return new InvoiceTable(
                    $factory->createGateway(
                        $sm->get(\Zend\Db\Adapter\Adapter::class),
                        $hydrator,
                        new Invoice(),
                        'invoices'
                    ),
                    $hydrator
                );
            },
            OrderHydrator::class => function ($sm) {
                return new OrderHydrator(
                    new ClassMethods(),
                    $sm->get(CustomerTable::class)
                );
            }
        ]
    ]
];
