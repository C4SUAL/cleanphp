<?php

namespace App\Providers;

use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use CleanPhp\Invoicer\Domain\Repository\InvoiceRepositoryInterface;
use CleanPhp\Invoicer\Domain\Repository\OrderRepositoryInterface;
use CleanPhp\Invoicer\Persistence\Doctrine\Repository\CustomerRepository;
use CleanPhp\Invoicer\Persistence\Doctrine\Repository\InvoiceRepository;
use CleanPhp\Invoicer\Persistence\Doctrine\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\ServiceProvider;
use Mrkrstphr\LaravelIndoctrinated\DoctrineOrmServiceProvider;
use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            InvoiceRepositoryInterface::class,
            function ($app) {
                return new InvoiceRepository(
                    $app[EntityManagerInterface::class]
                );
            }
        );

        $this->app->bind(
            OrderRepositoryInterface::class,
            function ($app) {
                return new OrderRepository(
                    $app[EntityManagerInterface::class]
                );
            }
        );

        $this->app->bind(
            CustomerRepositoryInterface::class,
            function ($app) {
                return new CustomerRepository(
                    $app[EntityManagerInterface::class]
                );
            }
        );

        $this->app->bind(
            HydratorInterface::class,
            function ($app) {
                return new ClassMethods();
            }
        );
    }
}
