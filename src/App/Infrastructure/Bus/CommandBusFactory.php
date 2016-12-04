<?php
namespace ToDDDoList\App\Infrastructure\Bus;

use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;

class CommandBusFactory
{
    public function createCommandBus($serviceLocator, $commandHandlersByCommandName)
    {
        $commandBus = new MessageBusSupportingMiddleware();
        $commandBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());

        $commandHandlerMap = new CallableMap(
            $commandHandlersByCommandName,
            new ServiceLocatorAwareCallableResolver(array($serviceLocator, 'getService'))
        );

        $commandNameResolver = new ClassBasedNameResolver();

        $commandHandlerResolver = new NameBasedMessageHandlerResolver(
            $commandNameResolver,
            $commandHandlerMap
        );

        $commandBus->appendMiddleware(
            new DelegatesToMessageHandlerMiddleware(
                $commandHandlerResolver
            )
        );

        return $commandBus;
    }
}
