<?php

namespace CleanPhp\Invoicer\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class RepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $class = 'CleanPhp\Invoicer\Persistence\Doctrine\Repository\\' . $requestedName;
        if (class_exists($class, true)) {
            return new $class(
                $container->get(EntityManager::class)
            );
        }
        throw new ServiceNotFoundException(
            'Unknown Repository requested: ' . $class
        );
    }
}