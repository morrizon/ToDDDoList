<?php
namespace ToDDDoList\Context\Task\Domain\Create;

use ToDDDoList\Context\Task\Domain\Exception\InvalidTaskArgumentException;

class CreateTaskCommandHandler
{
    private $factory;

    public function __construct(TaskFactory $factory)
    {
        $this->factory = $factory;
    }

    public function __invoke(CreateTaskCommand $command)
    {
        if (!$command->getTitle()) {
            throw new InvalidTaskArgumentException("Title must be a not empty string");
        }
        $this->factory->createTask($command->getTitle());
    }
}
