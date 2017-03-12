<?php
namespace ToDDDoList\App\Infrastructure\Bus;

use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;

class EventBusFactory
{
    public function createEventBus($serviceLocator, array $eventSubscribersByEventName)
    {
        $eventSubscriberCollection = new CallableCollection(
            $eventSubscribersByEventName,
            new ServiceLocatorAwareCallableResolver(array($serviceLocator, 'getService'))
        );
        $eventNameResolver = new ClassBasedNameResolver();
        $eventSubscribersResolver = new NameBasedMessageSubscriberResolver(
            $eventNameResolver,
            $eventSubscriberCollection
        );
        $eventBus = new MessageBusSupportingMiddleware();
        $eventBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());

        $eventBus->appendMiddleware(
            new NotifiesMessageSubscribersMiddleware(
                $eventSubscribersResolver
            )
        );
        return $eventBus;
    }
}
