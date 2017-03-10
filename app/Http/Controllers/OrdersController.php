<?php

namespace App\Http\Controllers;

use CleanPhp\Invoicer\Domain\Entity\Order;
use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Persistence\Hydrator\OrderHydrator;
use CleanPhp\Invoicer\Service\InputFilter\OrderInputFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;

/**
 * Class OrdersController
 * @package App\Http\Controllers
 */
class OrdersController extends Controller
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var OrderInputFilter
     */
    protected $inputFilter;

    /**
     * @var OrderHydrator
     */
    protected $hydrator;

    /**
     * OrdersController constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param OrderInputFilter $inputFilter
     * @param OrderHydrator $hydrator
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CustomerRepositoryInterface $customerRepository,
        OrderInputFilter $inputFilter,
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

    public function viewAction($id)
    {
        $order = $this->orderRepository->getById($id);

        if (!$order) {
            return new Response('', 404);
        }

        return view('orders/view', [
            "order" => $order
        ]);
    }

    public function newAction(Request $request)
    {
        $viewModel = [];
        $order = new Order();

        // IF POST
        if ($request->getMethod() == 'POST') {
            // add POST data to input filter
            $this->inputFilter->setData($request->request->all());

            if ($this->inputFilter->isValid()) {
                // If valid, hydrate the object and commit
                $order = $this->hydrator->hydrate($this->inputFilter->getValues(), $order);

                $this->orderRepository->begin();
                $this->orderRepository->persist($order);
                $this->orderRepository->commit();

                Session::flash('success', 'Order Saved');

                // redirect to view page
                return new RedirectResponse('/orders/view/' . $order->getId());
            } else {
                // else if invalid, hydrate with POST
                $this->hydrator->hydrate($request->request->all(), $order);

                $viewModel['error'] = $this->inputFilter->getMessages();
            }
        }
        // Load the view
        $viewModel['customers'] = $this->customerRepository->getAll();
        $viewModel['order'] = $order;

        return view('orders/new', $viewModel);
    }
}