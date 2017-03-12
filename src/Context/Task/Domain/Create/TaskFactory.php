<?php
namespace ToDDDoList\Context\Task\Domain\Create;

use ToDDDoList\Context\Task\Domain\Task;
use ToDDDoList\Context\Task\Domain\TaskRepository;

class TaskFactory
{
    private $repository;

    public function __construct(TaskRepository $repository, $eventBus)
    {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
    }

    public function createTask($title)
    {
        $done = false;
        $task = new Task($title, $done);
        $this->repository->save($task);

        $this->eventBus->handle(new TaskWasCreatedEvent);
    }
}
