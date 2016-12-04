<?php
namespace ToDDDoList\App;

use ToDDDoList\App\Infrastructure\InjectionContainer;
use ToDDDoList\App\Infrastructure\ConsoleApplication;

use ToDDDoList\App\Infrastructure\Bus\CommandBusFactory;
use ToDDDoList\Context\Task\Domain\Create\CreateTaskCommand;
use ToDDDoList\Context\Task\Domain\Create\CreateTaskCommandHandler;
use ToDDDoList\Context\Task\Domain\Create\TaskFactory;

use ToDDDoList\Context\Task\Infrastructure\Persistence\TaskRepositoryMemory;

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
    }

    /**
     * @return Container
     */
    public function getService($serviceName)
    {
        return $this->container->getService($serviceName);
    }
}
