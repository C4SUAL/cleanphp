<?php
namespace Application\Controller;

use CleanPhp\Invoicer\Domain\Repository\InvoiceRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;

class InvoicesController extends AbstractActionController
{
    protected $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoices)
    {
        $this->invoiceRepository = $invoices;
    }

    public function indexAction()
    {
        $invoices = $this->invoiceRepository->getAll();
        return [
            'invoices' => $invoices
        ];
    }
}