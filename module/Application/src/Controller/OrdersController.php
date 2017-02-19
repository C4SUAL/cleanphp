<?php

namespace Application\Controller;

use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OrdersController extends AbstractActionController
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    public function __construct(OrderRepositoryInterface $orders, CustomerRepositoryInterface $customers)
    {
        $this->orderRepository = $orders;
        $this->customerRepository = $customers;
    }

    public function indexAction()
    {
        return [
            'orders' => $this->orderRepository->getAll()
        ];
    }

    public function viewAction()
    {
        $id = $this->params()->fromRoute('id');
        $order = $this->orderRepository->getById($id);

        if (!$order) {
            $this->getResponse()->setStatusCode(404);
            return null;
        }

        return [
            'order' => $order
        ];
    }

    public function newAction()
    {
        $viewModel = new ViewModel();
        $order = new Order();

        $viewModel->setVariable('customers', $this->customerRepository->getAll());

        $viewModel->setVariable('order', $order);

        return $viewModel;
    }

}