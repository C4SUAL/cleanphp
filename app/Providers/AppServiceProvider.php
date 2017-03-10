<?php

namespace App\Providers;

use CleanPhp\Invoicer\Domain\Repository\CustomerRepositoryInterface;
use CleanPhp\Invoicer\Persistence\Doctrine\Repository\CustomerRepository;
use Illuminate\Support\ServiceProvider;
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
            CustomerRepositoryInterface::class,
            function ($app) {
                return new CustomerRepository(
                    $app['Doctrine\ORM\EntityManagerInterface']
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
