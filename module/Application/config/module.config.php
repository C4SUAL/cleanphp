<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\CustomersController;
use Application\Controller\InvoicesController;
use Application\Controller\OrdersController;
use Application\View\Helper\ValidationErrors;
use CleanPhp\Invoicer\Domain\Factory\InvoiceFactory;
use CleanPhp\Invoicer\Domain\Service\InvoicingService;
use CleanPhp\Invoicer\Persistence\Hydrator\OrderHydrator;
use CleanPhp\Invoicer\Service\InputFilter\CustomerInputFilter;
use CleanPhp\Invoicer\Service\InputFilter\OrderInputFilter;
use Zend\Hydrator\ClassMethods;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'customers' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/customers',
                    'defaults' => [
                        'controller' => Controller\CustomersController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'create' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/new',
//                            'constraints' => [
//                                'id' => '[0-9+]'
//                            ],
                            'defaults' => [
                                'action' => 'new-or-edit',
                            ],
                        ]
                    ],
                    'edit' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/edit/:id',
                            'constraints' => [
                                'id' => '[0-9+]'
                            ],
                            'defaults' => [
                                'action' => 'new-or-edit',
                            ],
                        ]
                    ],
                ]
            ],
            'orders' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/orders[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\OrdersController::class,
                        'action' => 'index',
                    ],
                ]
            ],
            'invoices' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/invoices[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\InvoicesController::class,
                        'action' => 'index',
                    ],
                ]
            ],
//            'application' => [
//                'type'    => Segment::class,
//                'options' => [
//                    'route'    => '/application[/:action]',
//                    'defaults' => [
//                        'controller' => Controller\IndexController::class,
//                        'action'     => 'index',
//                    ],
//                ],
//            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\CustomersController::class => function ($services) {
                return new CustomersController(
                    $services->get('CustomerRepository'),
                    new CustomerInputFilter(),
                    new ClassMethods()
                );
            },
            OrdersController::class => function ($services) {
                return new OrdersController(
                    $services->get('OrderRepository'),
                    $services->get('CustomerRepository'),
                    new OrderInputFilter(),
                    $services->get(OrderHydrator::class)
                );
            },
            InvoicesController::class => function ($services) {
                return new InvoicesController(
                    $services->get('InvoiceRepository'),
                    $services->get('OrderRepository'),
                    new InvoicingService(
                        $services->get('OrderRepository'),
                        new InvoiceFactory()
                    )
                );
            }
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'validationErrors' => ValidationErrors::class
        ]
    ]
];
