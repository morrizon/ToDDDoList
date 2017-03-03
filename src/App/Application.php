<?php
namespace ToDDDoList\App;

use ToDDDoList\App\Infrastructure\InjectionContainer;
use ToDDDoList\App\Infrastructure\ConsoleApplication;

use ToDDDoList\App\Infrastructure\Bus\CommandBusFactory;
use ToDDDoList\Context\Task\Domain\Create\CreateTaskCommand;
use ToDDDoList\Context\Task\Domain\Create\CreateTaskCommandHandler;
use ToDDDoList\Context\Task\Domain\Create\TaskFactory;

use ToDDDoList\Context\Task\Infrastructure\Persistence\TaskRepositoryMemory;

use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use ToDDDoList\Context\Task\Domain\Create\TaskWasCreatedEvent;
use ToDDDoList\Context\Task\Domain\Create\NotifyWhenTaskCreated;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;

class Application extends ConsoleApplication
{
    private $container;

    public function __construct()
    {
        parent::__construct();
        $this->container = new InjectionContainer();
    }

    public function registerServices()
    {
        $this->container->setService('task-repository', function ($c) {
            return new TaskRepositoryMemory();
        });

        $this->container->setService('task-factory', function ($c) {
            return new TaskFactory(
                $c->getService('task-repository')
            );
        });

        $this->container->setService(CreateTaskCommandHandler::class, function ($c) {
            return new CreateTaskCommandHandler(
                $c->getService('task-factory')
            );
        });

        $this->container->setService('command-bus', function ($c) {
            $commandHandlersByCommandName = [
                CreateTaskCommand::class => CreateTaskCommandHandler::class
            ];

            $factory = new CommandBusFactory();
            return $factory->createCommandBus(
                $c,
                $commandHandlersByCommandName
            );
        });

        $this->container->setService(NotifyWhenTaskCreated::class, function ($c) {
            return new NotifyWhenTaskCreated();
        });

        $this->container->setService('event-bus', function ($c) {
            // Provide a map of event names to callables. You can provide actual callables, or lazy-loading ones.
            $eventSubscribersByEventName = [
                TaskWasCreatedEvent::class => [
                    NotifyWhenTaskCreated::class,
                ],
            ];
			$serviceLocator = $this;//$app
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
        });
    }

    /**
     * @return Container
     */
    public function getService($serviceName)
    {
        return $this->container->getService($serviceName);
    }
}
