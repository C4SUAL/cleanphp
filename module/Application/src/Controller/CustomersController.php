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

    public function newOrEditAction()
    {
        $id = $this->params()->fromRoute('id');
        $customer = $id ? $this->customerRepository->getById($id) : new Customer();

        $viewModel = new ViewModel();

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
                $this->redirect()->toUrl('/customers/edit/' . $customer->getId());
            } else {
                $this->hydrator->hydrate(
                    $this->params()->fromPost(),
                    $customer
                );
                $viewModel->setVariable('errors', $this->inputFilter->getMessages());
            }
        }

        $viewModel->setVariables([
            'customer' => $customer,
            'titleAction' => !empty($customer->getId()) ? 'Edit' : 'New'
        ]);

        return $viewModel;
    }
}