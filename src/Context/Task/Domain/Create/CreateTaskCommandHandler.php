<?php
namespace ToDDDoList\Context\Task\Domain\Create;

class CreateTaskCommandHandler
{
    private $factory;

    public function __construct(TaskFactory $factory)
    {
        $this->factory = $factory;
    }

    public function __invoke(CreateTaskCommand $command)
    {
        $this->factory->createTask($command->getTitle());
    }
}
