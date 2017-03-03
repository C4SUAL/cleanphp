<?php

use CleanPhp\Invoicer\Domain\Entity\Invoice;
use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Persistence\Hydrator\InvoiceHydrator;
use CleanPhp\Invoicer\Persistence\Hydrator\OrderHydrator;
use Zend\Hydrator\ClassMethods;

describe('Persistence\Hydrator\InvoiceHydrator', function () {
    beforeEach(function () {
        $this->orderRepository = $this->getProphet()->prophesize(OrderRepositoryInterface::class);
        $this->hydrator = new InvoiceHydrator(
            new ClassMethods(),
            $this->orderRepository->reveal()
        );
    });
    describe('->extract()', function () {
        it('should perform simple extraction on the object', function () {
            $invoice = new Invoice();
            $invoice->setTotal(300.14);

            $data = $this->hydrator->extract($invoice);

            expect($data['total'])->to->equal($invoice->getTotal());
        });

        it('should extract a DateTime object to a string', function () {
            $invoice = new Invoice();
            $invoice->setInvoiceDate(new \DateTime('2017-03-28'));

            $data = $this->hydrator->extract($invoice);

            expect($data['invoice_date'])->to->equal($invoice->getInvoiceDate()->format('Y-m-d'));
        });

        it('should extract the order object', function () {
            $invoice = new Invoice();
            $order = new Order();
            $order->setTotal(300.12);
            $order->setId(14);
            $invoice->setOrder($order);

            $data = $this->hydrator->extract($invoice);

            expect($data['order_id'])->to->equal($invoice->getOrder()->getId());
        });
    });

    describe('->hydrate()', function () {
        it('should perform simple hydration on the object', function () {
            $data = [
                'total' => 300.14
            ];
            $invoice = $this->hydrator->hydrate($data, new Invoice());

            expect($invoice->getTotal())->to->equal($data['total']);
        });

        it('should hydrate a DateTime object', function () {
            $data = ['invoice_date' => '2015-09-16'];

            $invoice = $this->hydrator->hydrate($data, new Invoice());

            $expected = $invoice->getInvoiceDate()->format('Y-m-d');

            expect($expected)->to->equal($data['invoice_date']);
        });

        it('should hydrate an Order Entity on the Invoice', function () {
            $data = ['order_id' => 500];

            // Mock out the order repository
            $order = (new Order())->setId(500);
            $invoice = new Invoice();

            $this->orderRepository->getById(500)
                ->shouldBeCalled()
                ->willReturn($order);

            $this->hydrator->hydrate($data, $invoice);

            expect($invoice->getOrder())->to->equal($order);

            $this->getProphet()->checkPredictions();
        });

        it('should hydrate the embedded order data', function () {
            $data = ['order'=> ['id'=>500]];

            $invoice = new Invoice();

            $this->hydrator->hydrate($data, $invoice);

            $expected = $invoice->getOrder()->getId();

            expect($expected)->to->equal($data['order']['id']);
        });
    });
});