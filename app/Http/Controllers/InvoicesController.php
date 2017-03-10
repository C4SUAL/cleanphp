<?php

namespace App\Http\Controllers;

use CleanPhp\Invoicer\Domain\Repository\InvoiceRepositoryInterface;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Domain\Service\InvoicingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

/**
 * Class InvoicesController
 * @package App\Http\Controllers
 */
class InvoicesController extends Controller
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
    protected $invoicing;

    /**
     * InvoicesController constructor.
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param InvoicingService $invoicing
     */
    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        OrderRepositoryInterface $orderRepository,
        InvoicingService $invoicing
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->orderRepository = $orderRepository;
        $this->invoicing = $invoicing;
    }

    /**
     * List all invoices
     */
    public function indexAction()
    {
        $invoices = $this->invoiceRepository->getAll();

        return view('/invoices/index', ["invoices" => $invoices]);
    }

    /**
     * List all uninvoiced orders
     */
    public function newAction()
    {
        $orders = $this->orderRepository->getUninvoicedOrders();

        return view('/invoices/new', ["orders" => $orders]);
    }

    public function generateAction()
    {
        $invoices = $this->invoicing->generateInvoices();

        $this->invoiceRepository->begin();

        foreach ($invoices as $invoice) {
            $this->invoiceRepository->persist($invoice);
        }

        $this->invoiceRepository->commit();

        return view('/invoices/generate', ["invoices" => $invoices]);
    }

    public function viewAction($id)
    {
        $invoice = $this->invoiceRepository->getById($id);

        if (!$invoice) {
            return new Response('', 404);
        }

        return view('/invoices/view', [
            "invoice" => $invoice,
            "order" => $invoice->getOrder()
        ]);
    }
}