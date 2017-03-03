<?php
namespace Application\Controller;

use CleanPhp\Invoicer\Domain\Repository\InvoiceRepositoryInterface;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Domain\Service\InvoicingService;
use Zend\Mvc\Controller\AbstractActionController;

class InvoicesController extends AbstractActionController
{
    /**
     * @var InvoiceRepositoryInterface
     */
    protected $invoiceRepository;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var InvoicingService
     */
    protected $invoicingService;

    public function __construct(
        InvoiceRepositoryInterface $invoices,
        OrderRepositoryInterface $orderRepository,
        InvoicingService $invoicingService
    )
    {
        $this->invoiceRepository = $invoices;
        $this->orderRepository = $orderRepository;
        $this->invoicingService = $invoicingService;
    }

    public function indexAction()
    {
        $invoices = $this->invoiceRepository->getAll();
        return [
            'invoices' => $invoices
        ];
    }

    public function generateAction()
    {
        return [
            'orders' => $this->orderRepository->getUninvoicedOrders()
        ];
    }

    public function generateProcessAction()
    {
        $invoices = $this->invoicingService->generateInvoices();

        $this->invoiceRepository->begin();

        foreach ($invoices as $invoice) {
            $this->invoiceRepository->persist($invoice);
        }

        $this->invoiceRepository->commit();

        return [
            'invoices' => $invoices
        ];
    }
}