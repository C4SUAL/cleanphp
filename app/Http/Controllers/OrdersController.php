<?php

namespace App\Http\Controllers;

use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Persistence\Hydrator\OrderHydrator;
use Zend\InputFilter\InputFilter;

class OrdersController extends Controller
{
    protected $orderRepository;

    protected $customerRepository;

    protected $inputFilter;

    protected $hydrator;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CustomerRepositoryInterface $customerRepository,
        InputFilter $inputFilter,
        OrderHydrator $hydrator
    ) {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->hydrator = $hydrator;
        $this->inputFilter = $inputFilter;
    }

    public function indexAction()
    {
        return view('orders/index', [
            'orders' => $this->orderRepository->getAll()
        ]);
    }
}