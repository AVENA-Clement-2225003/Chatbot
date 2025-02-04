<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Nelmio\CorsBundle\Options\ResolverInterface;
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        parent::configureRoutes($routes);
        $routes->import('../config/routes/nelmio_cors.yaml');
    }
}

