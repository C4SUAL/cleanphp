<?php
/**
 *
 * User: Alan
 * Date: 11/02/2017
 */

namespace Application\Controller;

use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;

class CustomersController extends AbstractActionController
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customers)
    {
        $this->customerRepository = $customers;
    }

    public function indexAction()
    {
        return [
            'customers' => $this->customerRepository->getAll()
        ];
    }
}