<?php


namespace App\Http\Controllers;


use CleanPhp\Invoicer\Domain\Entity\Customer;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use CleanPhp\Invoicer\Service\InputFilter\CustomerInputFilter;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Zend\Hydrator\HydratorInterface;

class CustomersController extends Controller
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    /**
     * @var HydratorInterface
     */
    protected $hydrator;
    /**
     * @var CustomerInputFilter
     */
    protected $inputFilter;

    /**
     * CustomersController constructor.
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerInputFilter $inputFilter
     * @param HydratorInterface $hydrator
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerInputFilter $inputFilter,
        HydratorInterface $hydrator
    ) {
        $this->customerRepository = $customerRepository;
        $this->inputFilter = $inputFilter;
        $this->hydrator = $hydrator;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        $customers = $this->customerRepository->getAll();
        return view('customers/index', ['customers' => $customers]);
    }

    public function newOrEditAction(Request $request, $id = '')
    {
        $viewModel = [];

        $customer = $id
            ? $this->customerRepository->getById($id)
            : new Customer();

        if ($request->getMethod() == 'POST') {
            $this->inputFilter->setData($request->request->all());

            if ($this->inputFilter->isValid()){
                $this->hydrator->hydrate($this->inputFilter->getValues(), $customer);

                $this->customerRepository->begin();
                $this->customerRepository->persist($customer);
                $this->customerRepository->commit();

                Session::flash('success', 'Customer Saved');
                    return new RedirectResponse( '/customers/edit/' . $customer->getId()
                );
            } else {
                $this->hydrator->hydrate($request->all(), $customer);
                $viewModel['error'] = $this->inputFilter->getMessages();
            }
        } else {

        }
        $viewModel['customer'] = $customer;
        return view('customers/new-or-edit', $viewModel);
    }
}