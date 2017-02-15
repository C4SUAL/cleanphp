<?php
/**
 *
 * User: Alan
 * Date: 11/02/2017
 */

namespace Application\Controller;

use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use CleanPhp\Invoicer\Service\InputFilter\CustomerInputFilter;
use Zend\Hydrator\HydratorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CustomersController extends AbstractActionController
{
    protected $customerRepository;
    protected $inputFilter;
    protected $hydrator;

    public function __construct(
        CustomerRepositoryInterface $customers,
        CustomerInputFilter $inputFilter,
        HydratorInterface $hydrator
    )
    {
        $this->customerRepository = $customers;
        $this->inputFilter = $inputFilter;
        $this->hydrator = $hydrator;
    }

    public function indexAction()
    {
        return [
            'customers' => $this->customerRepository->getAll()
        ];
    }

    public function newAction()
    {
        $viewModel = new ViewModel();
        $customer = new Customer();

        if ($this->getRequest()->isPost()) {
            $this->inputFilter->setData(
                $this->params()->fromPost()
            );
            if ($this->inputFilter->isValid()) {
                $this->hydrator->hydrate(
                    $this->inputFilter->getValues(),
                    $customer
                );
                $this->customerRepository->begin()
                    ->persist($customer)
                    ->commit();

                $this->flashMessenger()->addSuccessMessage('Customer saved');
                $this->redirect()->toUrl('/customers');
            } else {
                $this->hydrator->hydrate(
                    $this->params()->fromPost(),
                    $customer
                );
                $viewModel->setVariable('errors', $this->inputFilter->getMessages());
            }
        }

        $viewModel->setVariable('customer', $customer);

        return $viewModel;
    }
}