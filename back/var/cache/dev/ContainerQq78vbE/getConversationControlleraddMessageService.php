<?php

namespace ContainerQq78vbE;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getConversationControlleraddMessageService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.xkU66BY.App\Controller\ConversationController::addMessage()' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.xkU66BY.App\\Controller\\ConversationController::addMessage()'] = ($container->privates['.service_locator.xkU66BY'] ?? $container->load('get_ServiceLocator_XkU66BYService'))->withContext('App\\Controller\\ConversationController::addMessage()', $container);
    }
}
